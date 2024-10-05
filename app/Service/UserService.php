<?php

namespace App\Service;

use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

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

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
        } catch (ModelNotFoundException $e) {
            Log::error("User id $id not found for deleting: " . $e->getMessage());
            throw new \Exception('The user with the given ID was not found.');
        } catch (\Exception $e) {
            Log::error("An unexpected error while deleting user id $id : " . $e->getMessage());
            throw new \Exception("An unexpected error while deleting user");
        }
    }

    public function forceDeleteUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->forceDelete();
        } catch (ModelNotFoundException $e) {
            Log::error("User id $id not found for force deleting: " . $e->getMessage());
            throw new \Exception('The user with the given ID was not found.');
        } catch (\Exception $e) {
            Log::error("An unexpected error while force deleting user id $id : " . $e->getMessage());
            throw new \Exception("An unexpected error while force  deleting user");
        }
    }

    public function restoreUser($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
        } catch (ModelNotFoundException $e) {
            Log::error("User id $id not found for restore: " . $e->getMessage());
            throw new \Exception('The user with the given ID was not found.');
        } catch (\Exception $e) {
            Log::error("An unexpected error while restoring user id $id : " . $e->getMessage());
            throw new \Exception("An unexpected error while restoring user");
        }
    }

    public function showUser($userId)
    {
        try {
            $user = User::with('tasksAssigned')->findOrFail($userId);
            return $user;
        } catch (ModelNotFoundException $e) {
            Log::error("Uere id $userId not found for retrieving: " . $e->getMessage());
            throw new Exception("User Not Found");
        } catch (Exception $e) {
            Log::error("An unexpected error while retrieving user id $userId: " . $e->getMessage());
            throw new Exception("An unexpected error while retrieving user");
        }
    }
}
