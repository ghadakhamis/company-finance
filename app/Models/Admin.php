<?php

namespace App\Models;

use App\Http\Resources\AdminResource;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Hash;

class Admin extends Authenticatable
{
    use HasFactory, HasApiTokens, SoftDeletes, Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected $guard = 'admin';

    /**
     * Get the resource for customer type.
     *
     * @return \App\Http\Resources\AdminResource
     */
    public function getResource()
    {
        return new AdminResource($this);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
