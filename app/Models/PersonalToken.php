<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalToken extends Model {
    use HasFactory;

    protected $fillable = [
        'userId', 'name', 'token', 'abilities', 'last_used_at'
    ];

    public function user() {
        return $this->hasOne(User::class, "id", "userId");
    }

}
