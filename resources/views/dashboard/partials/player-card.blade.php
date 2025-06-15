@php
    // Mapeo de posiciones a colores y etiquetas
    $positionMap = [
        'GK' => ['label' => 'Portero', 'class' => 'primary'],
        'DF' => ['label' => 'Defensa', 'class' => 'success'],
        'MF' => ['label' => 'Centrocampista', 'class' => 'warning'],
        'FW' => ['label' => 'Delantero', 'class' => 'danger'],
    ];
    
    $position = $position ?? 'MF'; // Valor por defecto si no se proporciona
    $positionData = $positionMap[$position] ?? $positionMap['MF'];
    
    // Datos de ejemplo (deberían venir del controlador)
    $player = $player ?? [
        'name' => 'Jugador de Prueba',
        'position' => $position,
        'age' => rand(18, 35),
        'value' => rand(1000000, 50000000),
        'wage' => rand(10000, 200000),
        'rating' => number_format(rand(30, 50) / 10, 1)
    ];
    
    // Porcentaje de la valoración (de 0 a 100 para la barra de progreso)
    $ratingPercent = ($player['rating'] / 5) * 100;
    
    // Clase para la tarjeta según la posición
    $cardClass = 'player-card card h-100';
    $badgeClass = 'badge bg-' . $positionData['class'] . ' bg-opacity-10 text-' . $positionData['class'] . ' position-badge';
    $positionClass = 'player-position bg-' . $positionData['class'] . ' text-white';
@endphp

<div class="col-md-3 mb-4">
    <div class="{{ $cardClass }}" data-position="{{ $position }}">
        <div class="card-body position-relative">
            <span class="{{ $positionClass }}">{{ $position }}</span>
            <div class="text-center mb-3">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="bi bi-person fs-1 text-secondary"></i>
                </div>
            </div>
            <h5 class="card-title text-center mb-1">{{ $player['name'] }}</h5>
            <p class="text-muted text-center mb-2">
                <span class="{{ $badgeClass }}">{{ $positionData['label'] }}</span>
            </p>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Edad:</span>
                <span class="fw-bold">{{ $player['age'] }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted">Valor:</span>
                <span class="fw-bold">@money($player['value'])</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Sueldo:</span>
                <span class="fw-bold">@money($player['wage'])<small>/semana</small></span>
            </div>
            <div class="text-center">
                <span class="player-rating text-{{ $positionData['class'] }}">{{ $player['rating'] }}</span>
                <div class="progress mt-1" style="height: 5px;">
                    <div class="progress-bar bg-{{ $positionData['class'] }}" 
                         role="progressbar" 
                         style="width: {{ $ratingPercent }}%;" 
                         aria-valuenow="{{ $ratingPercent }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-top-0">
            <div class="d-flex justify-content-between">
                <button class="btn btn-sm btn-outline-{{ $positionData['class'] }}">
                    <i class="bi bi-eye"></i> Detalles
                </button>
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="Entrenamiento individual">
                    <i class="bi bi-lightning"></i> Entrenar
                </button>
            </div>
        </div>
    </div>
</div>
