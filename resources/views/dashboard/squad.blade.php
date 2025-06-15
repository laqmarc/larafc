@extends('layouts.dashboard')

@section('title', 'Plantilla')

@push('styles')
<style>
    .player-card {
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
    }
    .player-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .player-position {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
    }
    .player-value {
        font-size: 0.9rem;
    }
    .player-rating {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .position-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .stats-card {
        height: 100%;
    }
    .nationality-badge {
        font-size: 0.75rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Plantilla</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-download"></i> Exportar
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-funnel"></i> Filtros
            </button>
        </div>
        <button type="button" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle"></i> Promover Cantera
        </button>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-2 col-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Total Jugadores</h6>
                <h3 class="mb-0">{{ $stats['total_players'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Media de Edad</h6>
                <h3 class="mb-0">{{ $stats['average_age'] }} <small>años</small></h3>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Media Valoración</h6>
                <h3 class="mb-0">{{ $stats['average_rating'] }}/5.0</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Valor Total</h6>
                <h3 class="mb-0">@money($stats['total_value'])</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-3">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Nómina Semanal</h6>
                <h3 class="mb-0">@money($stats['total_wage'])<small>/semana</small></h3>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar Jugador</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control" id="search" placeholder="Nombre del jugador...">
                </div>
            </div>
            <div class="col-md-3">
                <label for="position" class="form-label">Posición</label>
                <select class="form-select" id="position">
                    <option value="" selected>Todas las posiciones</option>
                    <option value="GK">Portero</option>
                    <option value="DF">Defensa</option>
                    <option value="MF">Centrocampista</option>
                    <option value="FW">Delantero</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="age" class="form-label">Edad</label>
                <select class="form-select" id="age">
                    <option value="" selected>Todas las edades</option>
                    <option value="u21">Menores de 21</option>
                    <option value="21-27">21 - 27 años</option>
                    <option value="28-32">28 - 32 años</option>
                    <option value="32+">Mayores de 32</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-primary w-100">Aplicar Filtros</button>
            </div>
        </div>
    </div>
</div>

<!-- Plantilla por Posiciones -->

<!-- Porteros -->
<div class="card mb-4">
    <div class="card-header bg-primary bg-opacity-10">
        <h5 class="mb-0">Porteros <span class="badge bg-primary rounded-pill">{{ count($squad['goalkeepers']) }}</span></h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($squad['goalkeepers'] as $player)
                @include('dashboard.partials.player-card', ['player' => $player])
            @empty
                <div class="col-12 text-center py-4 text-muted">
                    <i class="bi bi-people fs-1"></i>
                    <p class="mt-2 mb-0">No hay porteros en la plantilla</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Defensas -->
<div class="card mb-4">
    <div class="card-header bg-success bg-opacity-10">
        <h5 class="mb-0">Defensas <span class="badge bg-success rounded-pill">{{ count($squad['defenders']) }}</span></h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($squad['defenders'] as $player)
                @include('dashboard.partials.player-card', ['player' => $player])
            @empty
                <div class="col-12 text-center py-4 text-muted">
                    <i class="bi bi-people fs-1"></i>
                    <p class="mt-2 mb-0">No hay defensas en la plantilla</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Centrocampistas -->
<div class="card mb-4">
    <div class="card-header bg-warning bg-opacity-10">
        <h5 class="mb-0">Centrocampistas <span class="badge bg-warning text-dark rounded-pill">{{ count($squad['midfielders']) }}</span></h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            @forelse($squad['midfielders'] as $player)
                @include('dashboard.partials.player-card', ['player' => $player])
            @empty
                <div class="col-12 text-center py-4 text-muted">
                    <i class="bi bi-people fs-1"></i>
                    <p class="mt-2 mb-0">No hay centrocampistas en la plantilla</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Delanteros -->
<div class="card mb-4">
    <div class="card-header bg-danger bg-opacity-10">
        <h5 class="mb-0">Delanteros <span class="badge bg-danger rounded-pill">{{ count($squad['forwards']) }}</span></h5>
    </div>
    <div class="card-body">
        </div>
    </div>
</div>
@endif
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const playerCards = document.querySelectorAll('.player-card');
            
            playerCards.forEach(card => {
                const playerName = card.querySelector('.card-title').textContent.toLowerCase();
                if (playerName.includes(searchTerm)) {
                    card.closest('.col-md-3').style.display = 'block';
                } else {
                    card.closest('.col-md-3').style.display = 'none';
                }
            });
        });
    }

    // Filtro por posición
    const positionFilter = document.getElementById('position');
    if (positionFilter) {
        positionFilter.addEventListener('change', function() {
            const position = this.value;
            const positionGroups = document.querySelectorAll('[data-position-group]');
            
            if (!position) {
                // Mostrar todos los grupos si no hay filtro
                positionGroups.forEach(group => {
                    group.style.display = 'block';
                });
                return;
            }
            
            // Ocultar todos los grupos primero
            positionGroups.forEach(group => {
                group.style.display = 'none';
            });
            
            // Mostrar solo el grupo seleccionado
            const targetGroup = document.querySelector(`[data-position-group="${position}"]`);
            if (targetGroup) {
                targetGroup.style.display = 'block';
            }
        });
    }
});
</script>
@endpush
