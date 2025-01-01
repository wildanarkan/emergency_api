<?php
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($hospital) {
            // Delete related user (hospital admin)
            $hospital->admin()->delete();
            
            // Delete related patients
            $hospital->patients()->delete();
        });
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }
}