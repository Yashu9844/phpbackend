<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();  // ✅ Fixed Syntax
            $table->string('password');
            $table->string('profile_picture')->default("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQb3CGv17TYybA-cdAD1r6gbxJjX4YuXFJy-V1D29jPKw&s");
            $table->boolean('is_admin')->default(false);
            $table->timestamps();  // created_at & updated_at
        });
    }

    public function down() { // ✅ Fixed Typo
        Schema::dropIfExists('users');
    }
};
