<?php

namespace App\Models\core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Permission extends Model
{
    protected $table='core_permissions';
    protected $fillable = ['name'];

    public function roles(){
        return $this->belongsToMany(Role::class, 'core_role_user', 'user_id', 'role_id');
    }
    
}
