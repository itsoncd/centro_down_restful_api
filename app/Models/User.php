<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    // Los atributos que pueden ser asignados masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
        'confirmed',
        'isActive',
        'isVerified',
    ];
    public function roles()
{
    return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
}


    // Atributos que no se deberían mostrar al serializar el modelo
    protected $hidden = [
        'password', 
        'remember_token', // Para ocultar también el token de "remember me"
    ];

    // Atributos para los cuales se realizará una conversión a tipo
    protected $casts = [
        'email_verified_at' => 'datetime', // Convertir a formato de fecha y hora
        'password' => 'hashed', // Seguridad: Se indica que la contraseña está almacenada de forma segura
        'confirmed' => 'boolean', // Asegura que 'confirmed' es tratado como booleano
        'isActive' => 'boolean', // Asegura que 'isActive' es tratado como booleano
        'isVerified' => 'boolean', // Asegura que 'isVerified' es tratado como booleano
    ];

    // Relación con la tabla de Tokens (Un Usuario tiene muchos Tokens)
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    // Métodos necesarios para implementar JWTAuth
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Devuelve la clave primaria del usuario
    }

    public function getJWTCustomClaims()
{
    return [
        'email' => $this->email,
        'roles' => $this->roles()->pluck('name')->toArray()
    ];
}


    // Método para verificar si el usuario está activo
    public function isActive()
    {
        return $this->isActive; // Verifica si el usuario está activo
    }

    // Método para verificar si el usuario ha confirmado su cuenta
    public function isVerified()
    {
        return $this->isVerified; // Verifica si el usuario ha verificado su correo
    }
}