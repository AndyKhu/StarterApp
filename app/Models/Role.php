<?php
// app/Models/Role.php
namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name','status','icon'];
    protected $casts = [
        'status' => 'boolean',
    ];
}
