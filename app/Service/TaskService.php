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
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function getAll(Request $request)
    {
        //  can access from  Admin or  Manager
        if (!Gate::allows('getAllTask', Task::class)) {
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
        try {
            Gate::authorize('create', Task::class);
            return Task::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'due_date' => $data['due_date'],
                'status' => TaskStatus::NEW,
                'assigned_to' => $data['assigned_to'],
                'created_by' => Auth::id()
            ]);
        } catch (AuthorizationException $e) {
            throw new Exception("This action is unauthorized.");
        }
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
        if (!Gate::allows('updateStatusTask', $task)) {
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
        if (!Gate::authorize('assignTask', [$task, $assignedTo])) {
            throw new AuthorizationException('You are not authorized to update assign task');
        }
        $task->assigned_to = $assignedTo;
        $task->save();
        return $task;
    }

    public function deleteTask($id)
    {
        try {
            $task = Task::findOrFail($id);
            Gate::authorize('deleteTask', $task);
            $task->delete();
        } catch (ModelNotFoundException $e) {
            Log::error("Task id $id not found for deleting: " . $e->getMessage());
            throw new \Exception('The task with the given ID was not found.');
        } catch (\Exception $e) {
            Log::error("An unexpected error while deleting task id $id: " . $e->getMessage());
            throw new \Exception("An unexpected error while deleting task");
        }
    }

    public function forceDeleteTask($id)
    {
        try {
            $task = Task::onlyTrashed()->findOrFail($id);
            $task->forceDelete();
        } catch (ModelNotFoundException $e) {
            Log::error("Task id $id not found for force deleting: " . $e->getMessage());
            throw new \Exception('The task with the given ID was not found.');
        } catch (\Exception $e) {
            Log::error("An unexpected error while force deleting task id $id: " . $e->getMessage());
            throw new \Exception("An unexpected error while force deleting task");
        }
    }

    public function restoreTask($id)
    {
        try {
            $task = Task::onlyTrashed()->findOrFail($id);
            $task->restore();
        } catch (ModelNotFoundException $e) {
            Log::error("Task id $id not found for restoring: " . $e->getMessage());
            throw new \Exception('The task with the given ID was not found.');
        } catch (\Exception $e) {
            Log::error("An unexpected error while restoring task id $id: " . $e->getMessage());
            throw new \Exception("An unexpected error while restoring task");
        }
    }
}
