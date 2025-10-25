# Installation Guide

## 🚀 Quick Installation

### For New Laravel Projects

1. **Install the package**:
```bash
composer require laravel-dump/mac-viewer --dev
```

2. **Run setup** (optional):
```bash
php artisan laravel-dump:setup
```

3. **Start using**:
```php
app_dump('Hello from Laravel!');
```

### For Existing Laravel Projects

1. **Copy the setup script**:
```bash
curl -sSL https://raw.githubusercontent.com/sinarahmany/laravel-dump/main/setup-laravel-dump.php -o setup-laravel-dump.php
```

2. **Run the setup script**:
```bash
php setup-laravel-dump.php
```

3. **Start using**:
```php
app_dump('Hello from Laravel!');
```

## 🔧 Manual Installation

If you prefer manual setup:

1. **Copy the helper file**:
```bash
curl -sSL https://raw.githubusercontent.com/sinarahmany/laravel-dump/main/laravel-dump-helper.php -o laravel-dump-helper.php
```

2. **Include it in your routes** (add to `routes/web.php`):
```php
<?php

use Illuminate\Support\Facades\Route;

// Include Laravel Dump Helper
require_once base_path('laravel-dump-helper.php');

Route::get('/', function () {
    return view('welcome');
});
```

3. **Add test routes** (optional):
```php
// Laravel Dump Test Routes
Route::get('/test-dump', function () {
    app_dump('Hello from Laravel Dump!');
    app_dump(['user' => 'John', 'age' => 30, 'city' => 'New York']);
    return 'Dump test completed! Check your macOS Laravel Dump app.';
});

Route::get('/test-complex', function () {
    $data = [
        'users' => [
            ['name' => 'John', 'email' => 'john@example.com'],
            ['name' => 'Jane', 'email' => 'jane@example.com'],
        ],
        'settings' => [
            'theme' => 'dark',
            'notifications' => true,
        ],
        'timestamp' => now(),
    ];
    
    app_dump($data);
    return 'Complex dump test completed!';
});
```

## 🧪 Testing

1. **Start your Laravel server**:
```bash
php artisan serve
```

2. **Visit test URLs**:
- `http://localhost:8000/test-dump` - Simple dump test
- `http://localhost:8000/test-complex` - Complex data test

3. **Start your macOS Laravel Dump app** and watch the dumps appear!

## 🎯 What Gets Installed

- ✅ **Helper file**: `laravel-dump-helper.php` with all functions
- ✅ **Auto-include**: Automatically included in routes
- ✅ **Auto-detection**: Finds macOS app on ports 9999, 9998, 9997
- ✅ **Test routes**: Ready-to-use test endpoints
- ✅ **Error handling**: Graceful fallback if app isn't running

## 🚀 Usage

After installation, these functions are immediately available:

```php
// In any Laravel file (routes, controllers, models, etc.)
app_dump($data);           // Dump to macOS app
app_dd($data);            // Dump and die
app_var_dump($data);      // Var dump
app_print_r($data);       // Print_r

// Examples
app_dump(['user' => 'John', 'age' => 30]);
app_dd($request->all());
```

## 🔧 Configuration

The package works with zero configuration, but you can customize it:

```bash
php artisan vendor:publish --tag=laravel-dump-config
```

Then edit `config/laravel-dump.php`:

```php
return [
    'server_url' => 'http://localhost:9999',  // Custom server URL
    'auto_detect' => true,                   // Auto-detect macOS app
    'enabled' => true,                       // Enable/disable integration
    'ports' => [9999, 9998, 9997],         // Ports to check
    'timeout' => 1,                         // Request timeout
];
```

## 🌍 Environment Variables

```env
LARAVEL_DUMP_SERVER_URL=http://localhost:9999
LARAVEL_DUMP_AUTO_DETECT=true
LARAVEL_DUMP_ENABLED=true
LARAVEL_DUMP_TIMEOUT=1
```

## 📋 Requirements

- PHP 8.0+
- Laravel 9.0+
- macOS Laravel Dump Viewer app

## 🎯 That's It!

No Composer packages, no service providers, no complex configuration. Just:
1. Copy one file
2. Include it in routes
3. Start using the functions

The setup is truly zero-configuration! 🎉
