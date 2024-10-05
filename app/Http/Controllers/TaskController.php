<?php

namespace App\Http\Controllers;

use App\Http\Requests\assignTaskRequest;
use App\Http\Requests\storeTaskFrormRequest;
use App\Http\Requests\updateStatusTaskRequest;
use App\Models\Task;
use App\Service\TaskService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $tasks = $this->taskService->getAll($request);
            return $this->sendResponse($tasks, 'tasks have been retrieved successfully');
        } catch (AuthorizationException $e) {
            return $this->sendError('unAuthorization', ['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeTaskFrormRequest $request)
    {
        try {
            $validated = $request->validated();
            $task = $this->taskService->store($validated);
            return $this->sendResponse($task, 'task has been created successfully');
        } catch (Exception $e) {
            return $this->sendError('unAuthorization', ['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $task = $this->taskService->show($id);
            return $this->sendResponse($task, 'task has been successfully retrieved');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('get task failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(updateStatusTaskRequest $request, $taskId)
    {
        try {
            $task = $this->taskService->updateStatusTask($request->input('status'), $taskId);
            return $this->sendResponse($task, 'task has been updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('update failed', ['error' => $e->getMessage()]);
        } catch (AuthorizationException $e) {
            return $this->sendError('unAuthorization', ['error' => $e->getMessage()], 403);
        } catch (\Exception $e) {
            return $this->sendError('update failed', ['error' => $e->getMessage()], 400);
        }
    }

    public function assignTask(assignTaskRequest $request, $taskId)
    {
        try {

            $validated = $request->validated();
            $task = $this->taskService->assignTask($taskId, $validated['assigned_to']);
            return $this->sendResponse($task, 'task has been assigned successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('assign failed', ['error' => $e->getMessage()]);
        } catch (AuthorizationException $e) {
            return $this->sendError('unAuthorization', ['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->taskService->deleteTask($id);
            return response()->json(['message' => 'Task is deleted successfully'], 200);
        } catch (\Exception $e) {
            return $this->sendError(null,  $e->getMessage(), 404);
        }
    }

    public function forceDeleteTask($id)
    {
        try {
            $this->taskService->forceDeleteTask($id);
            return response()->json(['message' => 'Task is deleted Permanently'], 200);
        } catch (\Exception $e) {
            return $this->sendError(null,  $e->getMessage(), 404);
        }
    }
    public function restoreTask($id)
    {
        try {
            $this->taskService->restoreTask($id);
            return response()->json(['message' => 'Task is restored successfully'], 200);
        } catch (\Exception $e) {
            return $this->sendError(null,  $e->getMessage(), 404);
        }
    }
}
