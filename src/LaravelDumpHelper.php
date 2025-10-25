<?php

namespace LaravelDump\MacViewer;

/**
 * Laravel Dump Helper for macOS Viewer
 * 
 * This helper automatically integrates with the Laravel Dump macOS app
 * with zero configuration required.
 */
class LaravelDumpHelper
{
    private static $serverUrl = 'http://localhost:9999';
    private static $enabled = true;
    private static $autoDetect = true;
    
    /**
     * Enable or disable sending dumps to the macOS app
     */
    public static function setEnabled(bool $enabled): void
    {
        self::$enabled = $enabled;
    }
    
    /**
     * Set the server URL for the macOS app
     */
    public static function setServerUrl(string $url): void
    {
        self::$serverUrl = rtrim($url, '/');
        self::$autoDetect = false;
    }
    
    /**
     * Enable or disable auto-detection of the macOS app
     */
    public static function setAutoDetect(bool $autoDetect): void
    {
        self::$autoDetect = $autoDetect;
    }
    
    /**
     * Send dump data to the macOS app
     */
    public static function sendDump($data, string $type = 'dump', ?string $file = null, ?int $line = null): void
    {
        if (!self::$enabled) {
            return;
        }
        
        // Auto-detect server if enabled
        if (self::$autoDetect) {
            self::autoDetectServer();
        }
        
        $dumpData = [
            'content' => self::formatData($data),
            'type' => $type,
            'file' => $file ?: self::getCallerFile(),
            'line' => $line ?: self::getCallerLine(),
            'timestamp' => date('c'),
        ];
        
        self::sendToServer($dumpData);
    }
    
    /**
     * Auto-detect the macOS app server
     */
    private static function autoDetectServer(): void
    {
        $ports = [9999, 9998, 9997];
        
        foreach ($ports as $port) {
            if (self::isPortAvailable($port)) {
                self::$serverUrl = "http://localhost:{$port}";
                return;
            }
        }
    }
    
    /**
     * Check if a port is available
     */
    private static function isPortAvailable(int $port): bool
    {
        $connection = @fsockopen('localhost', $port, $errno, $errstr, 1);
        
        if ($connection) {
            fclose($connection);
            return true;
        }
        
        return false;
    }
    
    /**
     * Custom dump function that sends to macOS app
     */
    public static function dump($data): void
    {
        self::sendDump($data, 'dump');
    }
    
    /**
     * Custom dd function that sends to macOS app and dies
     */
    public static function dd($data): void
    {
        self::sendDump($data, 'dd');
        die();
    }
    
    /**
     * Custom var_dump function that sends to macOS app
     */
    public static function varDump($data): void
    {
        self::sendDump($data, 'var_dump');
    }
    
    /**
     * Custom print_r function that sends to macOS app
     */
    public static function printR($data): void
    {
        self::sendDump($data, 'print_r');
    }
    
    /**
     * Format data for display
     */
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
    
    /**
     * Get the caller file
     */
    private static function getCallerFile(): ?string
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $trace[2]['file'] ?? null;
    }
    
    /**
     * Get the caller line
     */
    private static function getCallerLine(): ?int
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        return $trace[2]['line'] ?? null;
    }
    
    /**
     * Send data to the macOS app server
     */
    private static function sendToServer(array $data): void
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => 'Content-Type: application/json',
                    'content' => json_encode($data),
                    'timeout' => 1, // Quick timeout to not block the app
                ]
            ]);
            
            @file_get_contents(self::$serverUrl, false, $context);
        } catch (\Exception $e) {
            // Silently fail if the macOS app is not running
        }
    }
}
