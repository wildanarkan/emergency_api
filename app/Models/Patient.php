<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $table = 'patient';

    protected $fillable = [
        'name',
        'age',
        'gender', // 1:male / 2:female
        'case',   // 1:non trauma / 2:trauma
        'desc',
        'arrival',
        'hospital_id',
        'user_id',
        'status'  // 1:menuju lokasi / 2:rujukan / 3:selesai
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'age' => 'integer',
        'gender' => 'integer',
        'case' => 'integer',
        'status' => 'integer',
        'arrival' => 'datetime'
    ];

    /**
     * Get the hospital associated with this patient.
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Get the user who created this patient record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the gender as text
     */
    public function getGenderTextAttribute(): string
    {
        return $this->gender === 1 ? 'Male' : 'Female';
    }

    /**
     * Get the case type as text
     */
    public function getCaseTextAttribute(): string
    {
        return $this->case === 1 ? 'Non Trauma' : 'Trauma';
    }

    /**
     * Get the status as text
     */
    public function getStatusTextAttribute(): string
    {
        switch ($this->status) {
            case 1:
                return 'Menuju Lokasi';
            case 2:
                return 'Rujukan';
            case 3:
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }
}
