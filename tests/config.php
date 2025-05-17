<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Phpstan gets funky med detta.
// if (method_exists(Dotenv::class, 'bootEnv')) {
//   (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
// }

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
