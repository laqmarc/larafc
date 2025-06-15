@extends('layouts.dashboard')

@section('title', 'Visión General')

@section('content')
<div class="team-header text-center mb-4">
    <h1>{{ $team->name }}</h1>
    <p class="mb-0">{{ $team->city }}, {{ $team->country }}</p>
    <p class="mb-0">Saldo: <strong>@money($financials['balance'])</strong></p>
</div>

<div class="row">
    <!-- Resumen Financiero -->
    <div class="col-md-6">
        <div class="card card-dashboard h-100">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Resumen Financiero</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="card financial-card">
                            <div class="card-body">
                                <h6 class="text-muted">Presupuesto de Fichajes</h6>
                                <h4>@money($financials['transfer_budget'])</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="card financial-card">
                            <div class="card-body">
                                <h6 class="text-muted">Nómina Semanal</h6>
                                <h4>@money($financials['weekly_wages'])<small class="text-muted">/semana</small></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h6>Ingresos Proyectados</h6>
                    <div class="progress mb-2" style="height: 20px;">
                        @php
                            $totalIncome = array_sum($financials['projected_income']);
                            $maxIncome = max(1000000, $totalIncome);
                        @endphp
                        @foreach($financials['projected_income'] as $type => $amount)
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ ($amount / $maxIncome) * 100 }}%" 
                                 title="{{ ucfirst($type) }}: @money($amount)">
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Total: @money($totalIncome)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos Partidos -->
    <div class="col-md-6">
        <div class="card card-dashboard h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Próximos Partidos</h5>
                <a href="#" class="btn btn-sm btn-outline-primary">Ver Calendario</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($upcomingMatches as $match)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $match['competition'] }}</h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($match['date'])->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="text-end">
                                <div>{{ $match['home_away'] == 'H' ? 'vs' : '@' }} {{ $match['opponent'] }}</div>
                                <span class="badge bg-{{ $match['home_away'] == 'H' ? 'primary' : 'secondary' }}">
                                    {{ $match['home_away'] == 'H' ? 'En Casa' : 'Fuera' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="list-group-item text-center text-muted">
                        No hay partidos programados
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Jugadores Clave -->
    <div class="col-md-12">
        <div class="card card-dashboard">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Jugadores Clave</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Posición</th>
                                <th>Edad</th>
                                <th>Valor</th>
                                <th>Sueldo</th>
                                <th>Valoración</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($keyPlayers as $player)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">
                                                <i class="bi bi-person-circle" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $player->name }}</div>
                                                <small class="text-muted">#{{ $player->jersey_number ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $player->position ?? 'N/A' }}</td>
                                    <td>{{ $player->age ?? 'N/A' }}</td>
                                    <td>@money($player->value ?? 0)</td>
                                    <td>@money($player->wage ?? 0)<small>/semana</small></td>
                                    <td>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ ($player->rating / 5) * 100 }}%" 
                                                 aria-valuenow="{{ $player->rating ?? 0 }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="5">
                                            </div>
                                        </div>
                                        <small>{{ $player->rating ?? '0' }}/5.0</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay jugadores en la plantilla</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Aquí puedes añadir scripts específicos para el dashboard
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection
