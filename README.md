# Laravel Packagist Explorer

A Laravel Artisan command to search, preview, and install PHP packages from Packagist.

## 🚀 Features
- Search for PHP packages directly from your terminal.
- Preview package details before installing.
- Open the package page in your browser.
- Install selected packages with Composer.

## 📦 Installation

You can install the package via Composer:

```bash
composer require your-org/packagist-explorer
```

## ⚡ Usage

### **Search and Install a Package**
Run the following command:

```bash
php artisan pack:install
```

1. **Enter a package name**: The command will ask you to enter a package name.
2. **Browse results**: A list of matching packages from Packagist will be displayed.
3. **Select a package**: Use arrow keys or enter the number to choose a package.
4. **Open the package page** *(Optional)*: You can open the package's Packagist URL in your browser.
5. **Confirm installation**: The command will ask if you want to install the package.
6. **Installation starts**: If confirmed, Composer will install the package.

### **Example Usage:**
```bash
php artisan pack:install
```
```
What is your package name? laravel-uuid
1. ramsey/uuid
   A PHP library for generating and working with universally unique identifiers (UUIDs).
2. spatie/laravel-uuid
   A simple Laravel package for handling UUIDs.
Show more results? (yes/no) no
Select a package: spatie/laravel-uuid - A simple Laravel package for handling UUIDs.
You selected: spatie/laravel-uuid
Do you want to open https://packagist.org/packages/spatie/laravel-uuid? (yes/no) yes
Opening URL...
Do you want to install spatie/laravel-uuid? (yes/no) yes
Installing spatie/laravel-uuid...
Package spatie/laravel-uuid installed successfully.
```

## 🧪 Testing

You can run tests using PHPUnit:

```bash
vendor/bin/phpunit
```

## 📝 License

This package is open-source and available under the [MIT License](LICENSE).
