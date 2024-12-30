<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'password',
        'name',
        'phone',
        'role', // 1:system admin / 2:hospital admin / 3:nurse
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the hospital managed by this user (for hospital admins).
     */
    public function hospital(): HasOne
    {
        return $this->hasOne(Hospital::class, 'user_id');
    }

    /**
     * Get all patients created by this user.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class, 'user_id');
    }

    /**
     * Get all notifications for this user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notif::class);
    }

    /**
     * Check if user is a system admin.
     */
    public function isSystemAdmin(): bool
    {
        return $this->role === 1;
    }

    /**
     * Check if user is a hospital admin.
     */
    public function isHospitalAdmin(): bool
    {
        return $this->role === 2;
    }

    /**
     * Check if user is a nurse.
     */
    public function isNurse(): bool
    {
        return $this->role === 3;
    }
}
