<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserService
{

    public function getAllUser()
    {
        return User::all();
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function UpdateUser(array $data, $id)
    {
        $user = User::find($id);
        if (!$user) {
            throw new ModelNotFoundException('The user with the given ID was not found.');
        }
        $user->update($data);
        return $user;
    }

    public function deleteUser($id){
        $user=User::find($id);
        if (!$user) {
            throw new ModelNotFoundException('The user with the given ID was not found.');
        }
        $user->delete();
    }
}
