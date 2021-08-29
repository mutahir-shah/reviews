<?php
declare(strict_types=1);

namespace App\Domain\Store;

interface StoreRepository
{
    /**
     * @return Store[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Store
     * @throws StoreNotFoundException
     */
   // public function findStoreOfId(int $id): Store;

   public function allStores():array;

   public function getDomainData(string $external_key):array;
}
