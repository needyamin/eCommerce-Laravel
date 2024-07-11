<?php
  
namespace App\Models;
  
use App\Models\core\Role; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
     protected $table = 'ecommerce_users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
  
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    /**
     * Get the attributes that should be cast.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    ##################################
    public function roles(){
        return $this->belongsToMany(Role::class, 'core_role_user', 'user_id', 'role_id');
    }
    

    public function hasAnyRole($roles){
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasAnyPermission($permissions){
        return $this->roles()->whereHas('permissions', function ($query) use ($permissions) {
            $query->whereIn('name', (array)$permissions);
        })->exists();
    }

    

    public function hasRole($roles){
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        return $this->roles()->whereIn('role_name', $roles)->exists();
    }

}