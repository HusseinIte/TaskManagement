<?php

namespace App\Policies;

use App\Enums\RoleUser;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user)
    {
        return $user->role === RoleUser::Admin || $user->role === RoleUser::Manager;
    }

    public function assignTask(User $user, Task $task, $assignedTo)
    {
        $assignedUser = User::find($assignedTo);
        if ($user->role === RoleUser::Manager && $assignedUser->role === RoleUser::Admin) {
            return false;
        }
        if ($user->role === RoleUser::Admin || ($user->role === RoleUser::Manager && $user->id === $task->created_by)) {
            return true;
        }
    }

    public function getAllTask(User $user)
    {
        return $user->role === RoleUser::Admin || $user->role === RoleUser::Manager;
    }

    public function updateStatusTask(User $user, Task $task)
    {
        return $user->id === $task->assigned_to;
    }


    public function deleteTask(User $user, Task $task)
    {
        if ($user->role === RoleUser::Admin || ($user->role === RoleUser::Manager && $user->id === $task->created_by)) {
            return true;
        }
    }
}
