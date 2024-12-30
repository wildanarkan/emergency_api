<?php
// app/Models/Hospital.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    protected $table = 'hospital';

    protected $fillable = [
        'name',
        'phone',
        'address',
        'user_id'
    ];

    /**
     * Get the admin user that manages this hospital.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all patients in this hospital.
     */
    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}