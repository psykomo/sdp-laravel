<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'make:module
        {name : Module name, e.g. InmateRegistry}
        {--force : Overwrite generated files if they already exist}
        {--without-registration : Skip provider registration in config/modules.php}';

    protected $description = 'Create a new modular-monolith module scaffold';

    public function handle(): int
    {
        $moduleName = Str::studly((string) $this->argument('name'));

        if ($moduleName === '') {
            $this->error('Module name cannot be empty.');

            return self::FAILURE;
        }

        $modulePath = app_path("Modules/{$moduleName}");

        if (File::exists($modulePath) && ! $this->option('force')) {
            $this->error("Module [{$moduleName}] already exists. Use --force to overwrite files.");

            return self::FAILURE;
        }

        $this->createDirectories($modulePath);
        $this->createFiles($moduleName, $modulePath);

        if (! $this->option('without-registration')) {
            $this->registerProvider($moduleName);
        }

        $this->info("Module [{$moduleName}] scaffold created successfully.");

        return self::SUCCESS;
    }

    private function createDirectories(string $modulePath): void
    {
        $directories = [
            'Domain',
            'Domain/Enums',
            'Application',
            'Application/Contracts',
            'Application/DataTransferObjects',
            'Infrastructure',
            'Infrastructure/Eloquent',
            'Infrastructure/Models',
            'Interfaces',
            'Interfaces/Http',
            'Interfaces/Http/Controllers',
            'Interfaces/Http/Requests',
            'Interfaces/Routes',
            'Database',
            'Database/Migrations',
            'Database/Factories',
        ];

        foreach ($directories as $directory) {
            File::ensureDirectoryExists($modulePath.'/'.$directory);
        }
    }

    private function createFiles(string $moduleName, string $modulePath): void
    {
        $placeholders = [
            '{{ module }}' => $moduleName,
            '{{ kebab_module }}' => Str::kebab($moduleName),
            '{{ dotted_module }}' => Str::of($moduleName)->snake()->replace('_', '-')->value(),
        ];

        $files = [
            'service-provider.stub' => $modulePath."/{$moduleName}ServiceProvider.php",
            'routes-web.stub' => $modulePath.'/Interfaces/Routes/web.php',
            'repository-contract.stub' => $modulePath."/Application/Contracts/{$moduleName}Repository.php",
            'repository-eloquent.stub' => $modulePath."/Infrastructure/Eloquent/Eloquent{$moduleName}Repository.php",
        ];

        foreach ($files as $stubFile => $destination) {
            if (File::exists($destination) && ! $this->option('force')) {
                continue;
            }

            $stubContent = File::get(base_path('stubs/modules/'.$stubFile));
            $compiledContent = str_replace(array_keys($placeholders), array_values($placeholders), $stubContent);

            File::put($destination, $compiledContent);
        }
    }

    private function registerProvider(string $moduleName): void
    {
        $configPath = config_path('modules.php');
        $providerClass = "App\\Modules\\{$moduleName}\\{$moduleName}ServiceProvider::class";

        if (! File::exists($configPath)) {
            $content = <<<PHP
<?php

declare(strict_types=1);

return [
    'providers' => [
        {$providerClass},
    ],
];
PHP;

            File::put($configPath, $content);

            return;
        }

        $content = File::get($configPath);

        if (Str::contains($content, $providerClass)) {
            return;
        }

        $needle = "    'providers' => [\n";

        if (Str::contains($content, $needle)) {
            $updated = str_replace($needle, $needle."        {$providerClass},\n", $content);
            File::put($configPath, $updated);

            return;
        }

        $this->warn("Could not auto-register provider [{$providerClass}] in config/modules.php.");
    }
}
