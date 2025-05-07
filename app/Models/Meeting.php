<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'client_id',
        'notes',
        'scheduled_at',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
