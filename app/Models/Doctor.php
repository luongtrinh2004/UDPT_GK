<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'specialty',
        'phone',
        'bio',
        'image',
        'working_hours'
    ];

    // Laravel sẽ cast thành array, nhưng chỉ an toàn với JSON kiểu MySQL.
    protected $casts = [
        'working_hours' => 'array',
    ];

    /**
     * Đảm bảo khi truy xuất dữ liệu working_hours là mảng
     * Fix trường hợp TEXT trong CockroachDB không tự cast được
     */
    public function getWorkingHoursAttribute($value)
    {
        if (is_array($value))
            return $value;

        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Tự động hash mật khẩu khi set thuộc tính
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }
}