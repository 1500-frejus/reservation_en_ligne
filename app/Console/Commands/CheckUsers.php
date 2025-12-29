<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier les utilisateurs dans la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::all();
        $this->info('Nombre total d\'utilisateurs: ' . $users->count());

        if ($users->count() > 0) {
            $this->table(
                ['ID', 'Name', 'Email', 'Role'],
                $users->map(function ($user) {
                    return [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role ?? 'N/A'
                    ];
                })->toArray()
            );
        } else {
            $this->warn('Aucun utilisateur trouvé dans la base de données.');
        }
    }
}
