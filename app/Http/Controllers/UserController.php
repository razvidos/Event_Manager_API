<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\ResponseStatus;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Event Manager API",
 *     version="1.0.0",
 *     description="Your API Description",
 *     @OA\Contact(
 *         email="ingvar.soloma@gmail.com"
 *     )
 * )
 * @OA\Server(
 *     url="/api"
 * )
 *
 * @OAS\SecurityScheme(
 *      securityScheme="bearer_token",
 *      type="http",
 *      scheme="bearer"
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", description="User ID"),
 *     @OA\Property(property="name", type="string", description="User name"),
 *     @OA\Property(property="email", type="string", format="email", description="User email"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time",
 *     description="User verified his email"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="User created at timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="User updated at timestamp")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     title="Update User Request",
 *     type="object",
 *     required={"name", "email"},
 *     properties={
 *         @OA\Property(property="name", type="string", description="User name"),
 *         @OA\Property(property="email", type="string", format="email", description="User email"),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="StoreUserRequest",
 *     title="Store User Request",
 *     type="object",
 *     required={
 *         "name",
 *         "email",
 *         "password"
 *     },
 *     properties={
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             description="Name of the user"
 *         ),
 *         @OA\Property(
 *             property="email",
 *             type="string",
 *             format="email",
 *             description="Email of the user"
 *         ),
 *         @OA\Property(
 *             property="password",
 *             type="string",
 *             format="password",
 *             description="Password of the user"
 *         ),
 *         @OA\Property(
 *             property="phone",
 *             type="string",
 *             description="Phone number of the user"
 *         ),
 *         @OA\Property(
 *             property="gender",
 *             type="string",
 *             enum={"male", "female", "other"},
 *             description="Gender of the user"
 *         ),
 *         @OA\Property(
 *             property="dob",
 *             type="string",
 *             format="date",
 *             description="Date of birth of the user"
 *         ),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="ErrorUserNotFound",
 *     title="Not Found Error",
 *     description="The requested resource could not be found but may be available in the future.",
 *     type="object",
 *     required={"message"},
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="User Not Found"
 *     )
 * )
 */
class UserController extends Controller
{

    const NOT_FOUND = 'User not found';


    public function __construct(protected UserRepositoryInterface $usersRepository)
    {
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     security={{"sunctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     ),
     *
     * )
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->usersRepository->getAll();
        return response()->json($users);
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Get User by ID",
     *     description="Get User by ID",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     security={{"sunctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorUserNotFound")
     *     ),
     * )
     *
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
     * @OA\Post(
     *     path="/users",
     *     summary="Create User",
     *     description="Create a new User",
     *     operationId="createUser",
     *     tags={"Users"},
     *     security={{"sunctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/StoreUserRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="field_name",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="The field_name field is required."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Update User",
     *     description="Update an existing User",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     security={{"sunctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the User to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UpdateUserRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/User"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorUserNotFound")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="The given data was invalid"
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="field_name",
     *                     type="array",
     *                     @OA\Items(
     *                         type="string",
     *                         example="The field_name field is required."
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     *
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
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Delete User",
     *     description="Delete a User by ID",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     security={{"sunctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the User to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorUserNotFound")
     *     ),
     * )
     *
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
