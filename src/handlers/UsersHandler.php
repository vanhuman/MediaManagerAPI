<?php

namespace Handlers;

use Models\User;
use PDO;
use Psr\Container\ContainerInterface;

class UsersHandler
{
    /* @var PDO $db */
    protected $db;

    public function __construct(ContainerInterface $container)
    {
        $this->db = $container->get('databaseConnection')->getDatabase();
    }

    /**
     * @return User | null
     * @throws \Exception
     */
    public function getUserById(int $userId)
    {
        $query = 'SELECT * FROM user WHERE `id` = ' . $userId;
        $result = $this->db->query($query);
        if ($result->rowCount() === 0) {
            return null;
        }
        $userData = $result->fetch();
        return $this->createModelFromDatabaseData($userData);
    }

    /**
     * @throws \Exception
     * @return User | null
     */
    public function getUserByCredentials(string $username)
    {
        $statement = $this->db->prepare('SELECT * FROM user WHERE `username` = :username');
        $statement->execute([':username' => $username]);
        if ($statement->rowCount() === 0) {
            return null;
        }
        $userData = $statement->fetch();
        return $this->createModelFromDatabaseData($userData);
    }

    /**
     * @return User
     */
    private function createModelFromDatabaseData(array $userData)
    {
        return new User([
            'id' => $userData['id'],
            'username' => $userData['username'],
            'password' => $userData['password'],
            'salt' => $userData['salt'],
            'admin' => $userData['admin'] == 1,
        ]);
    }
}
