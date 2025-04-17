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
        $key = 'verify_email:'.$this->id;

        $maxAttempts = 3;
        $decaySeconds = 60;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            abort(429, "請等待 {$seconds} 秒後再試");
        }

        RateLimiter::hit($key, $decaySeconds);

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
