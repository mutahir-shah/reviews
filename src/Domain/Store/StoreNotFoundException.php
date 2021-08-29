<?php
declare(strict_types=1);

namespace App\Domain\Store;

use App\Domain\DomainException\DomainRecordNotFoundException;

class StoreNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The Store you requested does not exist.';
}
