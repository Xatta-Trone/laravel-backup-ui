<?php

namespace XattaTrone\LaravelBackupUi\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelBackupUi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-backup-ui';
    }
}
