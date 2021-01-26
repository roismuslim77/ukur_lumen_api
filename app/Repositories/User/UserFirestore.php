<?php

namespace App\Repositories\User;

use Kreait\Laravel\Firebase\Facades\Firebase;

class UserFirestore implements UserRepositoryInterface
{
    protected $userCollection;

    public function __construct()
    {
        $this->userCollection = Firebase::firestore()
            ->database()
            ->collection('users');
    }

    public function all() : array
    {
        $documents = $this->userCollection->documents();
        $data = [];
        foreach ($documents as $value) {
            $row['id'] = $value->id();
            $user = array_merge($row, $value->data());
            $data[] = $user;
        }

        return $data;
    }
}