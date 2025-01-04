<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $table = 'patient';

    /**
     * Kolom yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'name',
        'age',
        'gender',       // 1:male / 2:female
        'case',         // 1:non trauma / 2:trauma
        'time_incident',// Waktu kejadian
        'mechanism',    // Mekanisme kejadian
        'injury',       // Detail cedera
        'photo_injury', // Foto cedera
        'treatment',    // Pengobatan yang diberikan
        'desc',
        'arrival',
        'hospital_id',
        'user_id',
        'status'        // 1:Menuju RS / 2:Selesai
    ];

    /**
     * Pengaturan casting tipe data.
     */
    protected $casts = [
        'age' => 'integer',
        'gender' => 'integer',
        'case' => 'integer',
        'status' => 'integer',
        'arrival' => 'datetime',
        'time_incident' => 'datetime' // Tambahan kolom baru
    ];

    /**
     * Relasi ke tabel hospital.
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Relasi ke tabel user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Aksesor untuk gender sebagai teks.
     */
    public function getGenderTextAttribute(): string
    {
        return $this->gender === 1 ? 'Male' : 'Female';
    }

    /**
     * Aksesor untuk tipe kasus sebagai teks.
     */
    public function getCaseTextAttribute(): string
    {
        return $this->case === 1 ? 'Non Trauma' : 'Trauma';
    }

    /**
     * Aksesor untuk status sebagai teks.
     */
    public function getStatusTextAttribute(): string
    {
        switch ($this->status) {
            case 1:
                return 'Menuju RS';
            case 2:
                return 'Selesai';
            default:
                return 'Unknown';
        }
    }

    /**
     * Aksesor untuk menampilkan waktu kejadian dalam format yang mudah dibaca.
     */
    public function getFormattedTimeIncidentAttribute(): string
    {
        return $this->time_incident ? $this->time_incident->format('d-m-Y H:i:s') : '-';
    }

    /**
     * Aksesor untuk memeriksa apakah ada foto cedera.
     */
    public function getHasPhotoInjuryAttribute(): bool
    {
        return !empty($this->photo_injury);
    }
}
