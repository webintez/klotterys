<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:reset-data {--force : Force the operation to run without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset lottery database (bookings, results, claims) while preserving admin credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('Are you sure you want to reset all lottery bookings, results, and claims data?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->info('Resetting database tables...');

        Schema::disableForeignKeyConstraints();
        DB::table('bookings')->truncate();
        DB::table('draw_results')->truncate();
        DB::table('prize_claims')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ensure default admin exists if no admin accounts exist
        $adminExists = User::where('is_admin', true)->exists();
        if (!$adminExists) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@keralajackpot.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]);
            $this->info('No admin user found. Seeded default admin: admin@keralajackpot.com / admin123');
        } else {
            $this->info('Admin user credentials preserved successfully.');
        }

        $this->info('Database data reset successfully!');
        return 0;
    }
}
