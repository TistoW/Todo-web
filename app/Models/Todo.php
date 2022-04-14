<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model {

    use HasFactory;

    protected $fillable = [
        'userId', 'name', 'deskripsi', 'isActive'
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];

    public function user() {
        return $this->hasOne(User::class, "id", "userId");
    }
}
