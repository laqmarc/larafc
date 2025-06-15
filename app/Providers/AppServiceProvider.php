<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar el helper de dinero
        require_once app_path('Helpers/money.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar directiva de Blade para formatear dinero
        Blade::directive('money', function ($expression) {
            return "<?php echo money($expression); ?>";
        });
    }
}
