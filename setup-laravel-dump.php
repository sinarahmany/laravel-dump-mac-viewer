<?php

/**
 * Laravel Dump macOS Viewer - Zero Configuration Setup Script
 * 
 * This script can be run from any Laravel project to set up the
 * Laravel Dump integration with zero configuration.
 * 
 * Usage: php setup-laravel-dump.php
 */

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ This doesn't appear to be a Laravel project (no artisan file found)\n";
    echo "Please run this script from your Laravel project root directory\n";
    exit(1);
}

echo "🚀 Laravel Dump macOS Viewer - Zero Configuration Setup\n";
echo "======================================================\n\n";

// Check if Composer is available
if (!function_exists('shell_exec') || !shell_exec('which composer')) {
    echo "❌ Composer is not available\n";
    echo "Please install Composer first: https://getcomposer.org/download/\n";
    exit(1);
}

echo "✅ Found Laravel project\n";
echo "✅ Composer is available\n\n";

// Install the package
echo "📦 Installing Laravel Dump macOS Viewer package...\n";
$output = shell_exec('composer require laravel-dump/mac-viewer --dev 2>&1');

if (strpos($output, 'Package laravel-dump/mac-viewer not found') !== false) {
    echo "⚠️  Package not found in Packagist yet\n";
    echo "This is expected for a new package. You can:\n";
    echo "1. Wait for the package to be published to Packagist\n";
    echo "2. Use the manual setup method (see README.md)\n";
    echo "3. Add the package as a local repository\n\n";
    
    echo "For now, let's set up the manual integration...\n";
    setupManualIntegration();
} else {
    echo "✅ Package installed successfully\n\n";
    
    // Run the setup command
    echo "🔧 Running Laravel Dump setup command...\n";
    $setupOutput = shell_exec('php artisan laravel-dump:setup --test 2>&1');
    echo $setupOutput . "\n";
}

echo "🎉 Setup complete!\n";
echo "You can now use these functions in your Laravel code:\n";
echo "• app_dump(\$data) - Dump data to macOS app\n";
echo "• app_dd(\$data) - Dump and die\n";
echo "• mac_dump(\$data) - Short alias\n";
echo "• mac_dd(\$data) - Short alias\n\n";
echo "Example: app_dump(['user' => 'John', 'age' => 30]);\n";

function setupManualIntegration() {
    echo "📝 Setting up manual integration...\n";
    
    // Create the helper file
    $helperContent = '<?php

/**
 * Laravel Dump Helper - Auto-generated
 * This file was created by the Laravel Dump setup script
 */

class LaravelDumpHelper
{
    private static $serverUrl = "http://localhost:9999";
    private static $enabled = true;
    
    public static function setEnabled(bool $enabled): void
    {
        self::$enabled = $enabled;
    }
    
    public static function setServerUrl(string $url): void
    {
        self::$serverUrl = rtrim($url, "/");
    }
    
    public static function sendDump($data, string $type = "dump", ?string $file = null, ?int $line = null): void
    {
        if (!self::$enabled) {
            return;
        }
        
        $dumpData = [
            "content" => self::formatData($data),
            "type" => $type,
            "file" => $file ?: self::getCallerFile(),
            "line" => $line ?: self::getCallerLine(),
            "timestamp" => date("c"),
        ];
        
        self::sendToServer($dumpData);
    }
    
    public static function dump($data): void
    {
        self::sendDump($data, "dump");
    }
    
    public static function dd($data): void
    {
        self::sendDump($data, "dd");
        die();
    }
    
    private static function formatData($data): string
    {
        if (is_string($data)) {
            return $data;
        }
        
        if (is_array($data) || is_object($data)) {
            return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
        
        return print_r($data, true);
    }
    
    private static function getCallerFile(): ?string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $trace[2]["file"] ?? null;
    }
    
    private static function getCallerLine(): ?int
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $trace[2]["line"] ?? null;
    }
    
    private static function sendToServer(array $data): void
    {
        try {
            $context = stream_context_create([
                "http" => [
                    "method" => "POST",
                    "header" => "Content-Type: application/json",
                    "content" => json_encode($data),
                    "timeout" => 1,
                ]
            ]);
            
            @file_get_contents(self::$serverUrl, false, $context);
        } catch (Exception $e) {
            // Silently fail if the macOS app is not running
        }
    }
}

// Global helper functions
if (!function_exists("app_dump")) {
    function app_dump($data): void
    {
        LaravelDumpHelper::dump($data);
    }
}

if (!function_exists("app_dd")) {
    function app_dd($data): void
    {
        LaravelDumpHelper::dd($data);
    }
}

if (!function_exists("mac_dump")) {
    function mac_dump($data): void
    {
        LaravelDumpHelper::dump($data);
    }
}

if (!function_exists("mac_dd")) {
    function mac_dd($data): void
    {
        LaravelDumpHelper::dd($data);
    }
}';

    file_put_contents('laravel-dump-helper.php', $helperContent);
    echo "✅ Created laravel-dump-helper.php\n";
    
    // Create a simple service provider
    $serviceProviderContent = '<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelDumpServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Auto-include the helper
        require_once base_path("laravel-dump-helper.php");
        
        // Auto-detect macOS app
        $this->autoDetectServer();
    }
    
    private function autoDetectServer(): void
    {
        $ports = [9999, 9998, 9997];
        
        foreach ($ports as $port) {
            $connection = @fsockopen("localhost", $port, $errno, $errstr, 1);
            if ($connection) {
                fclose($connection);
                LaravelDumpHelper::setServerUrl("http://localhost:{$port}");
                LaravelDumpHelper::setEnabled(true);
                break;
            }
        }
    }
}';

    file_put_contents('app/Providers/LaravelDumpServiceProvider.php', $serviceProviderContent);
    echo "✅ Created LaravelDumpServiceProvider\n";
    
    // Add to config/app.php
    $configAppPath = 'config/app.php';
    if (file_exists($configAppPath)) {
        $configContent = file_get_contents($configAppPath);
        
        // Add the service provider if not already added
        if (strpos($configContent, 'LaravelDumpServiceProvider') === false) {
            $configContent = str_replace(
                'App\Providers\RouteServiceProvider::class,',
                'App\Providers\RouteServiceProvider::class,
        App\Providers\LaravelDumpServiceProvider::class,',
                $configContent
            );
            
            file_put_contents($configAppPath, $configContent);
            echo "✅ Added service provider to config/app.php\n";
        }
    }
    
    echo "✅ Manual integration setup complete!\n";
    echo "The helper functions are now available globally.\n";
}
