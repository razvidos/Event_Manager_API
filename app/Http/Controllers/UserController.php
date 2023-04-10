<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\ResponseStatus;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    const NOT_FOUND = 'User not found';


    public function __construct(protected UserRepositoryInterface $usersRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->usersRepository->getAll();
        return response()->json($users);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->usersRepository->getById($id);
        if (!$user) {
            return response()->json(['message' => self::NOT_FOUND], ResponseStatus::NOT_FOUND);
        }
        return response()->json($user);
    }

    /**
     * @param StoreUserRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->usersRepository->create($data);
        return response()->json($user, ResponseStatus::CREATED);
    }

    /**
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $user = $this->usersRepository->getById($id);
        if (!$user) {
            return response()->json(['message' => self::NOT_FOUND], ResponseStatus::NOT_FOUND);
        }
        $data = $request->validated();
        $this->usersRepository->update($user, $data);
        return response()->json($user);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = $this->usersRepository->getById($id);
        if (!$user) {
            return response()->json(['message' => self::NOT_FOUND], ResponseStatus::NOT_FOUND);
        }
        $this->usersRepository->delete($user);
        return response()->json(null, ResponseStatus::NO_CONTENT);
    }
}
