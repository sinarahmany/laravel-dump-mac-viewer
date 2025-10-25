<?php

namespace LaravelDump\MacViewer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class LaravelDumpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-dump.php', 'laravel-dump');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Auto-register the dump helper
        $this->registerDumpHelper();
        
        // Register Artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \LaravelDump\MacViewer\Commands\LaravelDumpSetupCommand::class,
            ]);
            
            $this->publishes([
                __DIR__ . '/../config/laravel-dump.php' => config_path('laravel-dump.php'),
            ], 'laravel-dump-config');
        }
    }

    /**
     * Register the dump helper automatically
     */
    private function registerDumpHelper(): void
    {
        // Auto-detect if the macOS app is running
        $serverUrl = $this->detectServerUrl();
        
        if ($serverUrl) {
            LaravelDumpHelper::setServerUrl($serverUrl);
            LaravelDumpHelper::setEnabled(true);
        }
    }

    /**
     * Auto-detect the macOS app server URL
     */
    private function detectServerUrl(): ?string
    {
        // Try common ports
        $ports = [9999, 9998, 9997];
        
        foreach ($ports as $port) {
            if ($this->isPortAvailable($port)) {
                return "http://localhost:{$port}";
            }
        }
        
        return null;
    }

    /**
     * Check if a port is available (macOS app is running)
     */
    private function isPortAvailable(int $port): bool
    {
        $connection = @fsockopen('localhost', $port, $errno, $errstr, 1);
        
        if ($connection) {
            fclose($connection);
            return true;
        }
        
        return false;
    }
}
