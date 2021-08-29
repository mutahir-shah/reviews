<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\DbClasses\PDOdb; 
class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    /**
     * InMemoryUserRepository constructor.
     *
     * @param array|null $users
     */
     /**
     * @var PDO The database connection
     */
    private $db;

    public function __construct(array $users = null,PDOdb $connection)
    {
        $this->users = $users ?? [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ];
         $this->db = $connection;
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        $cols = "culture_name";
          $this->db->select('nl_culture',$cols); 
         $result = $this->db->fetch();

        return array_values($result);
    }

    /**
     * {@inheritdoc}
     */
    public function findUserOfId(int $id): User
    {
        if (!isset($this->users[$id])) {
            throw new UserNotFoundException();
        }

        return $this->users[$id];
    }
}
