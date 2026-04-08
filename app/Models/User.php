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
        'role',
        'team', // [AI Rules] Menambahkan kolom team agar bisa di-kelola di User Management
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
    // [HELPER] — Fungsi pembantu untuk mengecek otoritas user.
    // ------------------------------------------------------------

    /**
     * Mengecek apakah user adalah administrator.
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'administrator';
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

    /**
     * Relasi ke Task: Tugas-tugas di mana user ini ditunjuk sebagai pelaksana (Invited).
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany */
    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
