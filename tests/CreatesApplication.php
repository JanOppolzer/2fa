<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        // due to LdapRecord issues
        $_ENV['LDAP_LOGGING'] = false;

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
