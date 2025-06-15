<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'short_name',
        'tla',
        'crest_url',
        'address',
        'website',
        'founded',
        'club_colors',
        'venue',
        'balance',
        'transfer_budget',
        'wage_budget',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'founded' => 'integer',
        'balance' => 'decimal:2',
        'transfer_budget' => 'decimal:2',
        'wage_budget' => 'decimal:2',
    ];

    /**
     * Obtener los jugadores del equipo.
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    /**
     * Obtener las ligas a las que pertenece el equipo.
     */
    public function leagues(): BelongsToMany
    {
        return $this->belongsToMany(League::class, 'league_team')
                    ->withPivot('position', 'played', 'won', 'drawn', 'lost', 'goals_for', 'goals_against', 'goal_difference', 'points')
                    ->withTimestamps();
    }

    /**
     * Obtener el balance formateado del equipo.
     *
     * @return string
     */
    public function getFormattedBalanceAttribute(): string
    {
        return money($this->balance);
    }

    /**
     * Obtener el presupuesto de transferencias formateado.
     *
     * @return string
     */
    public function getFormattedTransferBudgetAttribute(): string
    {
        return money($this->transfer_budget);
    }

    /**
     * Obtener el presupuesto de salarios formateado.
     *
     * @return string
     */
    public function getFormattedWageBudgetAttribute(): string
    {
        return money($this->wage_budget);
    }

    /**
     * Obtener la URL del escudo del equipo.
     * Si no hay escudo, devuelve uno por defecto.
     *
     * @return string
     */
    public function getCrestUrlAttribute($value): string
    {
        return $value ?? asset('images/team-placeholder.png');
    }
}
