<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserFormRequest;
use App\Http\Requests\UpdateUserFormRequest;
use App\Models\User;
use App\Service\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->getAllUser();
        return $this->sendResponse($users, 'users have been retrieved succussfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserFormRequest $request)
    {
        $validated = $request->validated();
        $user = $this->userService->createUser($validated);
        return $this->sendResponse($user, 'user has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserFormRequest $request, string $userId)
    {
        try {
            $validated = $request->validated();
            $user = $this->userService->UpdateUser($validated, $userId);
            return $this->sendResponse($user, 'user has been updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendError('updated failed', ['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->userService->deleteUser($id);
            return response()->json(['message' => 'Book is deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return $this->sendError('delete failed', ['error' => $e->getMessage()], 404);
        }
    }
}
