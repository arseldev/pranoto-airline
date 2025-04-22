<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use  Notifiable,  SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getCreatedAtAttribute($value)
    {
        return formatDate($value);
    }

    // Scopes
    public function scopeCustomer($query)
    {
        return $query->where('is_admin', 0);
    }

    public function scopeAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

    // Relations
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id');
    }

    public function tenants()
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function licenses()
    {
        return $this->belongsToMany(License::class)
                    ->withTimestamps();
    }
    
    public function ads()
    {
        return $this->belongsToMany(Ad::class)
                    ->withTimestamps();
    }
    
    public function submissionDocuments()
    {
        return $this->belongsToMany(SubmissionDocument::class)
                    ->withPivot('tenant_id', 'file_path')
                    ->withTimestamps();
    }

    // Roles and Permissions
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function getAllPermissions()
    {
        return $this->roles()->with('permissions')->get()
            ->flatMap(function ($role) {
                return $role->permissions;
            })
            ->unique('id');
    }

    public function hasPermission($permissionName)
    {
        return $this->getAllPermissions()
            ->pluck('permission_name')
            ->contains($permissionName);
    }
    
}
