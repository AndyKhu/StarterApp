<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class CheckCrudPermission
{
    public function handle($request, Closure $next, $baseName = null)
    {
        $route = Route::current();
        $action = $route->getActionMethod();

        $map = [
            'index' => "view {$baseName}",
            'store' => "create {$baseName}",
            'update' => "edit {$baseName}",
            'destroy' => "delete {$baseName}",
        ];

        $permission = $map[$action] ?? null;

        if ($permission && !$request->user()->can($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
