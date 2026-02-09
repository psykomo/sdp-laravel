<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;

it('ensures all replicated tables have a version column', function (): void {
    $tables = [
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'wbp',
    ];

    foreach ($tables as $tableName) {
        expect(Schema::hasColumn($tableName, 'version'))
            ->toBeTrue("Table [{$tableName}] is missing the required version column.");
    }
});
