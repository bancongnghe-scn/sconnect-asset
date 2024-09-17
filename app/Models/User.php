<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Tắt tính năng auto-increment
    public $incrementing = false;

    // Đặt kiểu khóa chính nếu ID là chuỗi
    protected $keyType = 'int'; // 'string' nếu ID là chuỗi
    public const STATUS_ACTIVE = 1;
    public const USER_ID_ADMIN = 1;
    protected $fillable = [
        'id',
        'name',
        'email',
    ];
}
