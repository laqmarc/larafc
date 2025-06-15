<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Player extends Model
{
    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'team_id',
        'position',
        'jersey_number',
        'dob',
        'age',
        'nationality',
        'height_cm',
        'weight_kg',
        'preferred_foot',
        'photo_url',
        'rating',
        'value',
        'wage',
        'contract_until',
        'is_injured',
        'injury_details',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dob' => 'date',
        'contract_until' => 'date',
        'is_injured' => 'boolean',
        'height_cm' => 'integer',
        'weight_kg' => 'integer',
        'rating' => 'decimal:1',
        'value' => 'decimal:2',
        'wage' => 'decimal:2',
    ];

    /**
     * Obtener el equipo al que pertenece el jugador.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Obtener el nombre completo del jugador.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Obtener la posición formateada del jugador.
     *
     * @return string
     */
    public function getFormattedPositionAttribute(): string
    {
        $positions = [
            'GK' => 'Portero',
            'DF' => 'Defensa',
            'MF' => 'Centrocampista',
            'FW' => 'Delantero',
        ];

        return $positions[$this->position] ?? $this->position;
    }

    /**
     * Obtener la ruta de la foto del jugador.
     * Si no hay foto, devuelve una por defecto.
     *
     * @return string
     */
    public function getPhotoUrlAttribute($value): string
    {
        return $value ?? asset('images/player-placeholder.png');
    }

    /**
     * Obtener el valor formateado del jugador.
     *
     * @return string
     */
    public function getFormattedValueAttribute(): string
    {
        return money($this->value);
    }

    /**
     * Obtener el salario formateado del jugador.
     *
     * @return string
     */
    public function getFormattedWageAttribute(): string
    {
        return money($this->wage) . '/sem';
    }
}
