<?php

namespace App\Models;

use App\Enums\RoleType;
use App\Mail\CustomVerifyMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'time_limit',
        'suspend_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'time_limit' => 'datetime',
        ];
    }

    public function sendEmailVerificationNotification()
    {
        // 定義限制鍵名（使用用戶ID區分）
        $key = 'verify_email:'.$this->id;

        // 設置限制規則：每 60 秒最多 3 次
        $maxAttempts = 3;
        $decaySeconds = 60;

        // 檢查是否超過限制
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            abort(429, "請等待 {$seconds} 秒後再試");
        }

        // 計數 +1
        RateLimiter::hit($key, $decaySeconds);

        // 生成簽章 URL（有效期 60 分鐘）
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(5), // 驗證連結有效期
            ['id' => $this->id, 'hash' => sha1($this->email)]
        );

        Mail::to($this->email)->send(new CustomVerifyMail($verificationUrl, $this->name));
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function reports()
    {
        return $this->morphToMany(Report::class, 'reportable');
    }

    public function isAdmin()
    {
        return $this->hasRole(RoleType::Admin->value());
    }
}
