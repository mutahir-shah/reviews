<?php
declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface; 
use App\DbClasses\PDOdb;  

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings       = $c->get(SettingsInterface::class);
            $loggerSettings = $settings->get('logger');
            $logger         = new Logger($loggerSettings['name']);

            $processor      = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler        = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDOdb::class => function (ContainerInterface $c) {
            $settings   =  $c->get(SettingsInterface::class);
            $dbSettings = $settings->get('db');
            try { 
                $pdo = new PDOdb($dbSettings['host'],$dbSettings['dbname'], $dbSettings['user'], $dbSettings['pass']);
             } catch (Exception $exception) {
                throw new RuntimeException('Error establishing a database connection.');
            }
            return $pdo;
        },
        Memcached::class => function (ContainerInterface $c) {
            $settings   =  $c->get(SettingsInterface::class);
            $settings   = $settings->get('memcache');
            $memcache   = new \Memcached;
            if (!$memcache->addServer($settings['host'], $settings['port'])) {
                throw new RuntimeException('Could not connect to Memcache server');
            }
    
            return $memcache;
        },
    ]);
};
