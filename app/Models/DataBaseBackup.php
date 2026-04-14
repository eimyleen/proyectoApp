<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBaseBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'path',
    ];

    protected $casts = [
        'created_at' => HumanReadableTime::class,
        'updated_at' => HumanReadableTime::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
