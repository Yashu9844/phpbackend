<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['username', 'email', 'password', 'profile_picture', 'is_admin'];

    protected $hidden = ['password'];  // Hide password when returning JSON
    protected $attributes = [
        'profile_picture' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQb3CGv17TYybA-cdAD1r6gbxJjX4YuXFJy-V1D29jPKw&s',
        'is_admin' => false,
    ];
}
