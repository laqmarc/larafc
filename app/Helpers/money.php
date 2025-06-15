<?php

if (!function_exists('money')) {
    /**
     * Formatea una cantidad de dinero
     * 
     * @param float $amount Cantidad a formatear
     * @param int $decimals Número de decimales
     * @return string
     */
    function money($amount, $decimals = 0)
    {
        // Si el número es muy grande, mostrarlo en millones o miles de millones
        if ($amount >= 1000000000) {
            return '€' . number_format($amount / 1000000000, 1, ',', '.') . 'B';
        } elseif ($amount >= 1000000) {
            return '€' . number_format($amount / 1000000, 1, ',', '.') . 'M';
        } elseif ($amount >= 1000) {
            return '€' . number_format($amount / 1000, 1, ',', '.') . 'K';
        }
        
        // Para cantidades pequeñas, mostrar el valor completo
        return '€' . number_format($amount, $decimals, ',', '.');
    }
}
