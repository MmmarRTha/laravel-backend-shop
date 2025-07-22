<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbMonitor extends Command
{
    protected $signature = 'db:monitor';
    protected $description = 'Check database connection for deployment environments';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Checking database connection...');
        
        try {
            // Get connection config
            $connection = config('database.default');
            $this->info("Default connection: {$connection}");
            
            $config = config("database.connections.{$connection}");
            $this->info("Host: {$config['host']}");
            $this->info("Database: {$config['database']}");
            $this->info("Username: {$config['username']}");
            $this->info("Port: {$config['port']}");
            
            // Test connection
            $this->info('Attempting connection...');
            DB::connection()->getPdo();
            $this->info('Connection successful!');
            
            // List tables if connection succeeded
            $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
            
            if (count($tables) > 0) {
                $this->info('Existing tables:');
                foreach ($tables as $table) {
                    $this->info("- {$table->table_name}");
                }
            } else {
                $this->warn('No tables found in the database.');
            }
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Database connection failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
