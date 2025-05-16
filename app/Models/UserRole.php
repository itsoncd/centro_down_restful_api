<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Pivot
{
    // Si deseas personalizar el nombre de la tabla, puedes especificarlo aquí
    protected $table = 'user_roles';

    // Campos que pueden ser asignados en masa
    protected $fillable = [
        'user_id',
        'role_id',
    ];

    // Definir relaciones (si deseas acceder a otros modelos desde UserRole)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}