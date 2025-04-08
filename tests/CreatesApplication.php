<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\App;

trait CreatesApplication
{
    protected $appEnv;
    protected $env;

    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        // Add providers for tests
        // $app->register(TestServiceProvider::class);

        $app->make(Kernel::class)->bootstrap();

        // Extras settings
        config(['app.debug' => true]);

        $this->checkEnvironment();

        return $app;
    }

    protected function checkEnvironment(): void
    {
        $this->appEnv = App::environment();
        $this->env = getenv("APP_ENV");

        if ($this->appEnv !== 'testing' || $this->env !== 'testing') {
            $this->printEnvironmentError();
            exit(1);
        }
    }

    protected function printEnvironmentError(): void
    {
        $envFail = $this->appEnv !== 'testing' ? $this->appEnv : $this->env;

        // ANSI
        $red = "\033[31m";
        $yellow = "\033[33m";
        $blue = "\033[34m";
        $reset = "\033[0m";
        $bold = "\033[1m";

        $message = <<<ERROR

        {$red}╔══════════════════════════════════════════════════╗
        ║{$bold}          INCORRECT TEST ENVIRONMENT!             {$reset}{$red}║
        ╚══════════════════════════════════════════════════╝{$reset}

        {$yellow}● Environment detected:{$reset} {$red}{$bold}{$envFail}{$reset}

        {$blue}🛠  How to fix:{$reset}
        1. Create/configure the file {$bold}.env.testing{$reset} with {$bold}APP_ENV=testing{$reset}.
        2. Set the variables to something like this: {$bold}DB_CONNECTION=sqlite{$reset} and {$bold}DB_DATABASE=:memory:{$reset}
        3. Try:

            {$bold}php artisan config:clear && php artisan test && php artisan optimize{$reset}
            # This means that the {$bold}phpunit.xml{$reset} settings override those of {$bold}.env.testing{$reset}.
            # To use the {$bold}.env.testing{$reset} settings, comment or delete the line referring to the variable in {$bold}phpunit.xml{$reset}.

        4. With {$bold}php artisan optimize{$reset} a new {$bold}bootstrap/cache/config.php{$reset} will be generated for the environment defined in {$bold}.env{$reset}.
        5. For more information see: {$bold}https://laravel.com/docs/testing{$reset}


        {$yellow}⚠️  Tests can only be run in the 'testing' environment in this project.{$reset}

        ERROR;

        print ($message);
    }
}
