<?php

namespace App\Models\core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $table='core_roles';
    protected $fillable = ['name'];
    
    public function users(){
       return $this->belongsToMany(User::class);
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'core_permission_role');
    }

    
}
