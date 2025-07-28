<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/UserManagement/index', [
            'users' => UserResource::collectionArray(User::with(['roles', 'profile'])->get()),
            'roles' => RoleResource::collectionArray(Role::all()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'status' => 'required|boolean',
            'role_id' => 'required|exists:roles,id',
            'profile.full_name' => 'required|string|max:255',
            'profile.phone' => 'nullable|string|max:20',
            'profile.address' => 'nullable|string|max:255',
            'profile.avatar' => 'nullable|image|max:2048',
        ]);

        // Default avatar
        $defaultAvatar = 'avatars/default.webp';

        // Run transaction and return created profile
        $profile = DB::transaction(function () use ($validated, $defaultAvatar) {
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
            ]);
            $role = Role::findById($validated['role_id']);
            $user->assignRole($role);

            // Use tap() to return the created profile
            return tap(Profile::create([
                'user_id'   => $user->id,
                'full_name' => $validated['profile']['full_name'],
                'phone'     => $validated['profile']['phone'] ?? null,
                'address'   => $validated['profile']['address'] ?? null,
                'avatar'    => $defaultAvatar,
            ]));
        });

        // Upload avatar after DB commit
        if ($request->hasFile('profile.avatar')) {
            $uploadedPath = $request->file('profile.avatar')->store('avatars', 'public');
            $profile->update(['avatar' => $uploadedPath]);
        }

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username'          => "required|unique:users,username,{$user->id}",
            'password'          => 'nullable|min:6',
            'status'            => 'required|boolean',
            'role_id'           => 'required|exists:roles,id',
            'profile.full_name' => 'required|string|max:255',
            'profile.phone'     => 'nullable|string|max:20',
            'profile.address'   => 'nullable|string|max:255',
            'profile.avatar'    => 'nullable|image|max:2048',
        ]);
        $password = $validated['password'] ?? null;
        $defaultAvatar = 'avatars/default.webp';

        DB::transaction(function () use ($validated, $user, $password) {
            $user->update([
                'username' => $validated['username'],
                'password' => $password ? Hash::make($password) : $user->password,
                'status'   => $validated['status'],
            ]);

            // Sync role
            $role = Role::findById($validated['role_id']);
            $user->syncRoles([$role->name]);

            // Update profile (excluding avatar for now)
            $user->profile->update([
                'full_name' => $validated['profile']['full_name'],
                'phone'     => $validated['profile']['phone'] ?? null,
                'address'   => $validated['profile']['address'] ?? null,
            ]);
        });

        $profile = $user->profile;

        // Case 1: User uploaded a new avatar
        if ($request->hasFile('profile.avatar')) {
            // Delete old avatar if it's not default
            if ($profile->avatar && $profile->avatar !== $defaultAvatar) {
                Storage::disk('public')->delete($profile->avatar);
            }

            $uploadedPath = $request->file('profile.avatar')->store('avatars', 'public');
            $profile->update(['avatar' => $uploadedPath]);
        } else {
            if ($profile->avatar && $profile->avatar !== $defaultAvatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $profile->update(['avatar' => $defaultAvatar]);
        }

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'user deleted.');
    }
}
