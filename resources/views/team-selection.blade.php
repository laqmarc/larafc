<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecció d'equip - Laravel FC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .selection-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .loading {
            display: none;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="selection-container text-center">
            <div class="logo-container" style="width: 150px; height: 150px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; background-color: #f0f0f0; border-radius: 8px;">
                <span style="text-align: center; padding: 10px; font-weight: bold;">Laravel FC</span>
            </div>
            <h1 class="h3 mb-4">Selecciona el teu equip</h1>
            
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form id="teamSelectionForm" action="{{ route('select.team') }}" method="POST">
                @csrf
                <input type="hidden" name="league_id" id="leagueId" value="">
                
                <div class="mb-4">
                    <h4 class="text-muted mb-3">Temporada Actual</h4>
                    <div class="alert alert-info">
                        <strong>{{ $currentSeason->name }}</strong><br>
                        {{ \Carbon\Carbon::parse($currentSeason->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($currentSeason->end_date)->format('d/m/Y') }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="league" class="form-label">Competició</label>
                    <select class="form-select" id="league" required>
                        <option value="" selected disabled>Selecciona una competició</option>
                        @foreach($leagues as $league)
                            <option value="{{ $league->id }}">
                                {{ $league->name }} 
                                @if($league->country)
                                    ({{ $league->country }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text">Selecciona una competició per veure els equips participants.</div>
                </div>

                <div class="mb-4">
                    <label for="team" class="form-label">Equip</label>
                    <select class="form-select" id="team" name="team_id" required disabled>
                        <option value="" selected disabled>Primer selecciona una competició</option>
                    </select>
                    <div id="teamLoading" class="loading mt-2">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Carregant...</span>
                        </div>
                        Carregant equips...
                    </div>
                    <div id="teamInfo" class="mt-2" style="display: none;">
                        <small class="text-muted">
                            <span id="teamCity"></span>
                            <span id="teamStadium" class="ms-2"></span>
                        </small>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" id="submitBtn" disabled>
                    Seleccionar equip
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Mostrar información del usuario autenticado
        console.log('=== INFORMACIÓN DEL USUARIO AUTENTICADO ===');
        console.log('Usuario ID:', {{ Auth::id() ?? 'null' }});
        console.log('Nombre de usuario:', '{{ Auth::user()->name ?? "No autenticado" }}');
        console.log('Email:', '{{ Auth::user()->email ?? "No disponible" }}');
        console.log('==========================================');

        $(document).ready(function() {
            // Mostrar información cuando se carga la página
            console.log('=== PÁGINA CARGADA ===');
            console.log('URL actual:', window.location.href);
            console.log('Equipo seleccionado en sesión:', {{ session('selected_team_id') ?? 'null' }});
            console.log('Liga seleccionada en sesión:', {{ session('selected_league_id') ?? 'null' }});
            console.log('Temporada actual en sesión:', {{ session('selected_season_id') ?? 'null' }});
            console.log('==========================');
            
            // Cuando cambia la liga seleccionada
            $('#league').change(function() {
                const leagueId = $(this).val();
                const leagueName = $('#league option:selected').text().trim();
                
                // Actualizar el campo oculto del formulario
                $('#leagueId').val(leagueId);
                
                console.log('=== CAMBIO DE LIGA ===');
                console.log('Liga seleccionada - ID:', leagueId, 'Nombre:', leagueName);
                console.log('Campo oculto actualizado a:', $('#leagueId').val());
                console.log('====================');
                const $teamSelect = $('#team');
                const $teamLoading = $('#teamLoading');
                const $submitBtn = $('#submitBtn');
                
                if (!leagueId) {
                    $teamSelect.prop('disabled', true).html('<option value="" selected disabled>Primer selecciona una lliga</option>');
                    $submitBtn.prop('disabled', true);
                    return;
                }
                
                // Mostrar carga
                $teamSelect.prop('disabled', true);
                $teamLoading.show();
                $submitBtn.prop('disabled', true);
                
                // Obtener equipos de la liga seleccionada
                $.get(`/get-teams/${leagueId}`, function(teams) {
                    let options = '<option value="" selected disabled>Selecciona un equip</option>';
                    
                    if (teams.length > 0) {
                        teams.forEach(function(team) {
                            options += `<option value="${team.id}">${team.name}</option>`;
                        });
                        $teamSelect.html(options).prop('disabled', false);
                    } else {
                        $teamSelect.html('<option value="" selected disabled>No s\'han trobat equips</option>');
                    }
                    
                    $teamLoading.hide();
                }).fail(function() {
                    $teamSelect.html('<option value="" selected disabled>Error en carregar els equips</option>');
                    $teamLoading.hide();
                });
            });
            
            // Cuando se selecciona un equipo
            $('#team').change(function() {
                const teamId = $(this).val();
                const teamName = $('#team option:selected').text().trim();
                
                console.log('=== CAMBIO DE EQUIPO ===');
                console.log('Equipo seleccionado - ID:', teamId, 'Nombre:', teamName);
                console.log('Usuario autenticado - ID:', {{ Auth::id() ?? 'null' }});
                console.log('========================');
                $('#submitBtn').prop('disabled', !$(this).val());
            });
            
            // Manejar el envío del formulario
            $('#teamSelectionForm').submit(function(e) {
                const teamId = $('#team').val();
                const leagueId = $('#league').val();
                
                console.log('=== INTENTO DE ENVÍO DE FORMULARIO ===');
                console.log('Team ID seleccionado:', teamId);
                console.log('League ID seleccionada:', leagueId);
                console.log('Valor del campo oculto league_id:', $('#leagueId').val());
                
                if (!teamId) {
                    e.preventDefault();
                    console.log('Error: No se ha seleccionado un equipo');
                    alert('Si us plau, selecciona un equip');
                } else if (!leagueId) {
                    e.preventDefault();
                    console.log('Error: No se ha seleccionado una liga');
                    alert('Si us plau, selecciona una lliga');
                } else {
                    console.log('Formulario enviado correctamente');
                }
            });
        });
    </script>
</body>
</html>
