<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckTeamSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Rutas que no requieren verificación de equipo seleccionado
        $excludedRoutes = ['select.team.form', 'select.team', 'logout'];
        
        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }
        
        // Verificar si el usuario está autenticado y ha seleccionado un equipo
        if (Auth::check() && !session('selected_team_id')) {
            return redirect()->route('select.team.form')
                ->with('error', 'Si us plau, selecciona un equip abans de continuar.');
        }
        
        return $next($request);
    }
}
