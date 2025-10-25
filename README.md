# Laravel Dump macOS Viewer - Composer Package

A zero-configuration Laravel package that integrates with the Laravel Dump macOS Viewer app for real-time debugging.

## 🚀 Features

- **Zero Configuration** - Works out of the box
- **Auto-Detection** - Automatically finds the macOS app
- **Multiple Functions** - `app_dump()`, `app_dd()`, `app_var_dump()`, `app_print_r()`
- **Smart Fallback** - Graceful handling when macOS app isn't running
- **Laravel Integration** - Works in routes, controllers, models, commands

## 📦 Installation

### Option 1: Composer Package (Recommended)

```bash
composer require sinarahmany/laravel-dump-mac-viewer --dev
php artisan laravel-dump:setup
```

### Option 2: Automated Installer

```bash
curl -sSL https://raw.githubusercontent.com/sinarahmany/laravel-dump/main/install.sh | bash
```

### Option 3: Manual Setup

```bash
curl -sSL https://raw.githubusercontent.com/sinarahmany/laravel-dump/main/setup-laravel-dump.php | php
```

## 🎮 Usage

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

## 🧪 Testing

1. **Start your Laravel server**:
```bash
php artisan serve
```

2. **Visit test URLs**:
- `http://localhost:8000/test-dump` - Simple dump test
- `http://localhost:8000/test-complex` - Complex data test

3. **Start your macOS Laravel Dump app** and watch the dumps appear!

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

## 🎯 How It Works

1. **Auto-Detection**: Automatically finds the macOS app on ports 9999, 9998, 9997
2. **Zero Configuration**: No manual setup required
3. **Smart Fallback**: Graceful handling when app isn't running
4. **Performance**: 1-second timeout to avoid blocking your app

## 🚀 Quick Start

```bash
# Install the package
composer require sinarahmany/laravel-dump-mac-viewer --dev

# Run setup (optional)
php artisan laravel-dump:setup

# Start using immediately
app_dump('Hello from Laravel!');
```

## 📖 Documentation

- [Full Documentation](https://github.com/sinarahmany/laravel-dump)
- [macOS App Repository](https://github.com/sinarahmany/laravel-dump-macos)

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

**Sina Rahmannejad**
- GitHub: [@sinarahmany](https://github.com/sinarahmany)
- Website: [sinarahmannejad.com](https://sinarahmannejad.com)
- LinkedIn: [sina-rahmannejad](https://linkedin.com/in/sina-rahmannejad)

## 🙏 Acknowledgments

- Laravel Framework
- macOS Development Community
- Open Source Contributors
