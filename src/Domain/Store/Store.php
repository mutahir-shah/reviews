<?php
declare(strict_types=1);

namespace App\Domain\Store;

use JsonSerializable;

class Store implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;
 

    /**
     * @param int|null  $id 
     */
    public function __construct(?int $id, string $username, string $firstName, string $lastName)
    {
        $this->id = $id; 
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    } 

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [];
    }
}
