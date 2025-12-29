<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les routes dans la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = \App\Models\Route::all();
        $this->info('Nombre total de routes: ' . $routes->count());

        if ($routes->count() > 0) {
            $this->table(
                ['ID', 'Départ', 'Arrivée', 'Prix'],
                $routes->map(function ($route) {
                    return [
                        $route->id,
                        $route->depart,
                        $route->arrivee,
                        $route->prix . '€'
                    ];
                })->toArray()
            );
        } else {
            $this->warn('Aucune route trouvée dans la base de données.');
        }
    }
}
