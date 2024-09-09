<?php

namespace App\Service;

use App\Enums\RoleUser;
use App\Enums\TaskStatus;
use App\Models\Task;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskService
{
    public function getAll(Request $request)
    {
        //  can access from  Admin or  Manager
        if (!Gate::allows('get-all-task')) {
            throw new AuthorizationException('You are not authorized to get all task');
        }
        $query = Task::query();
        if ($request->filled('priority')) {
            $query->priority($request->input('priority'));
        }
        if ($request->filled('status')) {
            $query->status($request->input('status'));
        }
        return $query->with('createdBy', 'assignedTo')->get();
    }

    public function store(array $data)
    {
        if (!Gate::allows('create-task')) {
            throw new AuthorizationException('You are not authorized to create task');
        }
        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'due_date' => $data['due_date'],
            'status' => TaskStatus::NEW,
            'assigned_to' => $data['assigned_to'],
            'created_by' => Auth::id()
        ]);
    }
    public function show($id)
    {
        $task = Task::with('createdBy', 'assignedTo')->find($id);
        if (!$task) {
            throw new ModelNotFoundException('The task with the given ID was not found.');
        }
        return $task;
    }
    // update status from assigned user
    public function updateStatusTask($NewStatus, $taskId)
    {

        $task = Task::find($taskId);
        if (!$task) {
            throw new ModelNotFoundException('The task with the given ID was not found.');
        }
        if (!Gate::allows('update-status-task', $task)) {
            throw new AuthorizationException('You are not authorized to update status task');
        }
        if (in_array($NewStatus, [TaskStatus::CANCELED->value, TaskStatus::FAILED->value])) {
            $task->status = $NewStatus;
            $task->save();
            return $task;
        }
        $nextStatus = $this->canChangedStatus($task->status, $NewStatus);
        $task->status = $nextStatus;
        $task->save();
        return $task;
    }
    // To ensure a correct transition to the next step  Ex: New -> In progress -> Completed
    public function canChangedStatus($currentStatus, $NewStatus)
    {

        $nextStatus = $currentStatus->next();
        if ($nextStatus && $nextStatus->value == $NewStatus) {
            return $nextStatus;
        } else {
            throw new \Exception('can not move to new status from this status');
        }
    }

    public function assignTask($taskId, $assignedTo)
    {
        $task = Task::find($taskId);
        if (!$task) {
            throw new ModelNotFoundException('The task with the given ID was not found.');
        }
        // can access from created user (Manager) and any admin
        if (!Gate::allows('assign-status-task', $task)) {
            throw new AuthorizationException('You are not authorized to update assign task');
        }
        $task->assigned_to = $assignedTo;
        $task->save();
        return $task;
    }

    public function deleteTask($id)
    {
        $task = Task::find($id);
        if (!$task) {
            throw new ModelNotFoundException('The task with the given ID was not found.');
        }
        // can access from created user (Manager) and any admin
        if (!Gate::allows('delete-task', $task)) {
            throw new AuthorizationException('You are not authorized to delete task');
        }
        $task->delete();
    }
}
