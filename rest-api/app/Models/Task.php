<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'status', 'completed_at', 'user_id',
    ];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    public function user()
    {
        return $this->belongsTo('\App\Models\User');
    }
}
