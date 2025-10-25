<?php

namespace LaravelDump\MacViewer\Commands;

use Illuminate\Console\Command;
use LaravelDump\MacViewer\LaravelDumpHelper;

class LaravelDumpSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'laravel-dump:setup 
                            {--server-url= : Set the server URL manually}
                            {--disable-auto-detect : Disable auto-detection}
                            {--test : Test the connection after setup}';

    /**
     * The console command description.
     */
    protected $description = 'Setup Laravel Dump macOS Viewer integration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Setting up Laravel Dump macOS Viewer...');
        
        // Check if macOS app is running
        if (!$this->checkMacOSApp()) {
            $this->error('❌ Laravel Dump macOS app is not running!');
            $this->line('');
            $this->line('Please:');
            $this->line('1. Open the Laravel Dump macOS app');
            $this->line('2. Make sure it\'s listening on a port (9999, 9998, or 9997)');
            $this->line('3. Run this command again');
            return 1;
        }
        
        // Configure the helper
        $this->configureHelper();
        
        // Test connection if requested
        if ($this->option('test')) {
            $this->testConnection();
        }
        
        $this->info('✅ Laravel Dump setup complete!');
        $this->line('');
        $this->line('You can now use these functions in your Laravel code:');
        $this->line('• app_dump($data) - Dump data to macOS app');
        $this->line('• app_dd($data) - Dump and die');
        $this->line('• mac_dump($data) - Short alias');
        $this->line('• mac_dd($data) - Short alias');
        
        return 0;
    }
    
    /**
     * Check if the macOS app is running
     */
    private function checkMacOSApp(): bool
    {
        $ports = [9999, 9998, 9997];
        
        foreach ($ports as $port) {
            if ($this->isPortAvailable($port)) {
                $this->info("✅ Found Laravel Dump app on port {$port}");
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if a port is available
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
    
    /**
     * Configure the helper
     */
    private function configureHelper(): void
    {
        if ($serverUrl = $this->option('server-url')) {
            LaravelDumpHelper::setServerUrl($serverUrl);
            $this->info("✅ Server URL set to: {$serverUrl}");
        }
        
        if ($this->option('disable-auto-detect')) {
            LaravelDumpHelper::setAutoDetect(false);
            $this->info('✅ Auto-detection disabled');
        }
        
        LaravelDumpHelper::setEnabled(true);
        $this->info('✅ Laravel Dump integration enabled');
    }
    
    /**
     * Test the connection
     */
    private function testConnection(): void
    {
        $this->info('🧪 Testing connection...');
        
        try {
            LaravelDumpHelper::dump('Test connection from Laravel Dump setup command');
            $this->info('✅ Test dump sent successfully!');
            $this->line('Check your macOS app to see the test dump.');
        } catch (\Exception $e) {
            $this->error('❌ Failed to send test dump: ' . $e->getMessage());
        }
    }
}
