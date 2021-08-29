<?php
declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Domain\Store\StoreRepository;
use App\Infrastructure\Persistence\Store\InMemoryStoreRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository,StoreRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        StoreRepository::class => \DI\autowire(InMemoryStoreRepository::class),
    ]);
};
