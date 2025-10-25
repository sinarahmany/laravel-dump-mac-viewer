<?php

use LaravelDump\MacViewer\LaravelDumpHelper;

/**
 * Global helper functions for Laravel Dump macOS Viewer
 * 
 * These functions are automatically available after installing the package.
 * No configuration required - they auto-detect the macOS app.
 */

if (!function_exists('app_dump')) {
    /**
     * Dump data to the macOS Laravel Dump Viewer
     * 
     * @param mixed $data The data to dump
     */
    function app_dump($data): void
    {
        LaravelDumpHelper::dump($data);
    }
}

if (!function_exists('app_dd')) {
    /**
     * Dump data to the macOS Laravel Dump Viewer and die
     * 
     * @param mixed $data The data to dump
     */
    function app_dd($data): void
    {
        LaravelDumpHelper::dd($data);
    }
}

if (!function_exists('app_var_dump')) {
    /**
     * Var dump data to the macOS Laravel Dump Viewer
     * 
     * @param mixed $data The data to dump
     */
    function app_var_dump($data): void
    {
        LaravelDumpHelper::varDump($data);
    }
}

if (!function_exists('app_print_r')) {
    /**
     * Print_r data to the macOS Laravel Dump Viewer
     * 
     * @param mixed $data The data to dump
     */
    function app_print_r($data): void
    {
        LaravelDumpHelper::printR($data);
    }
}

if (!function_exists('mac_dump')) {
    /**
     * Alias for app_dump() - shorter syntax
     * 
     * @param mixed $data The data to dump
     */
    function mac_dump($data): void
    {
        LaravelDumpHelper::dump($data);
    }
}

if (!function_exists('mac_dd')) {
    /**
     * Alias for app_dd() - shorter syntax
     * 
     * @param mixed $data The data to dump
     */
    function mac_dd($data): void
    {
        LaravelDumpHelper::dd($data);
    }
}
