<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
        ];
    }

    // ------------------------------------------------------------
    // [RELASI] — Setiap User memiliki koleksi Catatan, Task, dan Setting.
    // ------------------------------------------------------------

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
