<?php

use App\Kernel as AppKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}

$kernel = new AppKernel('test', true);
$application = new ConsoleApplication($kernel);
$application->setAutoExit(false);

$commands = [
    ['command' => 'doctrine:database:create', '--if-not-exists' => true],
    ['command' => 'doctrine:migration:migrate', '--no-interaction' => true, '--allow-no-migration' => true],
];

foreach ($commands as $command) {
    $application->run(new ArrayInput($command));
}