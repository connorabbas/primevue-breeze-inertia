<?php

namespace App\Models;

use App\Notifications\AdminVerifyEmail;
use Illuminate\Notifications\Notifiable;
//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable //implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;

    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected function getDefaultGuardName(): string
    {
        return 'admin';
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new AdminVerifyEmail());
    }
}
