<?php
declare(strict_types=1);

namespace App\Application\Actions\Store;

use Psr\Http\Message\ResponseInterface as Response;

class ListStoresAction extends StoreAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $stores = $this->storeRepository->allStores();

        $this->logger->info("Stores list was viewed.");

        return $this->respondWithData($stores);
    }
}
