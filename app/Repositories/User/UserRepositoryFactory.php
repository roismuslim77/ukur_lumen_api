<?php
namespace App\Repositories\User;
use RuntimeException;

class UserRepositoryFactory 
{
    const FIRESTORE = 'firestore';

    public function make($database)
    {
        switch ($database) {
            case self::FIRESTORE:
                return new UserFirestore;
            default:
                throw new RuntimeException('Unknown Repository: ' . $database);
        }
    }
}

