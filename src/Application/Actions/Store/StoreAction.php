<?php
declare(strict_types=1);

namespace App\Application\Actions\Store;

use App\Application\Actions\Action;
use App\Domain\Store\StoreRepository;
use Psr\Log\LoggerInterface;

abstract class StoreAction extends Action
{
    /**
     * @var StoreRepository
     */
    protected $storeRepository;

    /**
     * @param LoggerInterface $logger
     * @param StoreRepository $storeRepository
     */
    public function __construct(LoggerInterface $logger,
    StoreRepository $storeRepository
    ) {
        parent::__construct($logger);
        $this->storeRepository = $storeRepository;
    }
}
