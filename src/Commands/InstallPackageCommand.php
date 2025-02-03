<?php

namespace Pack\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\text;
use function Laravel\Prompts\spin;

class InstallPackageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pack:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a PHP package from Packagist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $packageName = text(
            'What is your package name?',
            required: 'Package name is required'
        );

        $getPackage = spin(
            callback: function () use ($packageName) {
                $page = 1;
                $perPage = 14;
                $totalResults = [];

                do {
                    $response = Http::get("https://packagist.org/search.json?q={$packageName}&page={$page}");
                    $jsonResponse = $response->json();

                    $packages = $jsonResponse['results'] ?? [];

                    if (empty($packages)) {
                        break;
                    }

                    // Show packages in console
                    foreach ($packages as $index => $package) {
                        $number = (($page - 1) * $perPage) + ($index + 1);
                        $this->line("{$number}. <fg=blue>{$package['name']}</>");
                        $this->line("<fg=gray>{$package['description']}</>");
                    }


                    $totalResults = array_merge($totalResults, $packages);
                    $page++;

                    // Ask user if they want more results
                    if (!$this->confirm("Show more results?")) {
                        break;
                    }
                } while (true);

                return $totalResults;
            },
            message: 'Getting package info'
        );

        // Use the correct data structure (since $getPackage is an array of results)
        $packages = collect($getPackage)
            ->mapWithKeys(fn($pkg) => [
                $pkg['name'] => [
                    'description' => $pkg['description'] ?: 'No description available',
                    'url' => $pkg['url'] ?? null,
                ]
            ])
            ->toArray();

        if (empty($packages)) {
            $this->warn('No packages found.');
            return;
        }

        // Format options with colored package names
        $options = collect($packages)
            ->map(fn($data, $name) => "{$name} - <fg=blue>{$data['description']}</>")
            ->values()
            ->toArray();

        // Display a select input for the user to choose a package
        $selectedIndex = $this->choice('Select a package:', $options);

        // Extract the actual package name
        $selectedPackage = explode(' - ', strip_tags($selectedIndex), 2)[0];

        $this->info("You selected: {$selectedPackage}");

        if ($this->confirm("Do you want to open {$packages[$selectedPackage]['url']}?")) {
            $this->openUrl($packages[$selectedPackage]['url']);
            $this->info('Opening URL...');
        }

        // Confirm installation
        if (!$this->confirm("Do you want to install {$selectedPackage}?", true)) {
            $this->warn('Installation canceled.');
            return;
        }

        // Use Artisan to run composer require command
        $this->info("Installing {$selectedPackage}...");

        // install package
        $result = Process::run('composer require ' . $selectedPackage);

        if ($result->successful()) {
            $this->info("Package {$selectedPackage} installed successfully.");
        } else {
            $this->error("Failed to install package. Please check the error logs.");
        }
    }

    /**
     * Open URL in the default web browser
     */
    protected function openUrl($url)
    {
        switch (PHP_OS_FAMILY) {
            case 'Windows':
                exec("start {$url}");
                break;
            case 'Darwin': // macOS
                exec("open {$url}");
                break;
            default: // Linux
                exec("xdg-open {$url}");
                break;
        }
    }
}
