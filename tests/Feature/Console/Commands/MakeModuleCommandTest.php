<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('creates a new module scaffold and registers its provider', function (): void {
    $moduleName = 'CaseManagement';
    $modulePath = app_path('Modules/'.$moduleName);
    $modulesConfigPath = config_path('modules.php');
    $modulesConfigBackup = File::get($modulesConfigPath);

    File::deleteDirectory($modulePath);

    $this->artisan('make:module '.$moduleName)
        ->expectsOutput("Module [{$moduleName}] scaffold created successfully.")
        ->assertExitCode(0);

    expect(File::isDirectory($modulePath))->toBeTrue();
    expect(File::exists($modulePath.'/Application/Contracts/'.$moduleName.'Repository.php'))->toBeTrue();
    expect(File::exists($modulePath.'/Infrastructure/Eloquent/Eloquent'.$moduleName.'Repository.php'))->toBeTrue();
    expect(File::exists($modulePath.'/Interfaces/Routes/web.php'))->toBeTrue();
    expect(File::exists($modulePath.'/'.$moduleName.'ServiceProvider.php'))->toBeTrue();

    $configContent = File::get($modulesConfigPath);

    expect($configContent)->toContain("App\\Modules\\{$moduleName}\\{$moduleName}ServiceProvider::class");

    File::deleteDirectory($modulePath);
    File::put($modulesConfigPath, $modulesConfigBackup);
});

it('fails when module exists without force flag', function (): void {
    $moduleName = 'CaseManagement';
    $modulePath = app_path('Modules/'.$moduleName);

    File::ensureDirectoryExists($modulePath);

    $this->artisan('make:module '.$moduleName)
        ->expectsOutput("Module [{$moduleName}] already exists. Use --force to overwrite files.")
        ->assertExitCode(1);

    File::deleteDirectory($modulePath);
});
