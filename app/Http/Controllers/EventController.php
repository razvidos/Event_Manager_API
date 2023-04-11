<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\ResponseStatus;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Event",
 *     title="Event",
 *     description="Event model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Event ID"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Event title"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Event description"
 *     ),
 *     @OA\Property(
 *         property="location",
 *         type="string",
 *         description="Event location"
 *     ),
 *     @OA\Property(
 *         property="start_time",
 *         type="string",
 *         format="date-time",
 *         description="Event start time"
 *     ),
 *     @OA\Property(
 *         property="end_time",
 *         type="string",
 *         format="date-time",
 *         description="Event end time"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Event created at timestamp"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Event updated at timestamp"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="StoreEventRequest",
 *     title="Store Event Request",
 *     type="object",
 *     required={"title", "location", "start_time", "end_time"},
 *     properties={
 *         @OA\Property(
 *             property="title",
 *             type="string",
 *             description="Event title"
 *         ),
 *         @OA\Property(
 *             property="description",
 *             type="string",
 *             description="Event description"
 *         ),
 *         @OA\Property(
 *             property="location",
 *             type="string",
 *             description="Event location"
 *         ),
 *         @OA\Property(
 *             property="start_time",
 *             type="string",
 *             format="date-time",
 *             description="Event start time"
 *         ),
 *         @OA\Property(
 *             property="end_time",
 *             type="string",
 *             format="date-time",
 *             description="Event end time"
 *         ),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="UpdateEventRequest",
 *     title="Update Event Request",
 *     type="object",
 *     properties={
 *         @OA\Property(
 *             property="title",
 *             type="string",
 *             description="Event title"
 *         ),
 *         @OA\Property(
 *             property="description",
 *             type="string",
 *             description="Event description"
 *         ),
 *         @OA\Property(
 *             property="location",
 *             type="string",
 *             description="Event location"
 *         ),
 *         @OA\Property(
 *             property="start_time",
 *             type="string",
 *             format="date-time",
 *             description="Event start time"
 *         ),
 *         @OA\Property(
 *             property="end_time",
 *             type="string",
 *             format="date-time",
 *             description="Event end time"
 *         ),
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="ErrorEventNotFound",
 *     title="Not Found Error",
 *     description="The requested resource could not be found but may be available in the future.",
 *     type="object",
 *     required={"message"},
 *     @OA\Property(
 *         property="message",
 *         type="string",
 *         example="Event Not Found"
 *     )
 * )
 */
class EventController extends Controller
{
    const NOT_FOUND = 'Event not found';


    public function __construct(protected EventRepositoryInterface $eventsRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $events = $this->eventsRepository->getAll();
        return response()->json($events);
    }

    /**
     * @OA\Get(
     *     path="/events",
     *     summary="Get all events",
     *     tags={"Events"},
     *     security={{"sunctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Event")
     *         )
     *     ),
     *
     * )
     *
     * @OA\Get(
     *     path="/events/{id}",
     *     summary="Get Event by ID",
     *     description="Get Event by ID",
     *     operationId="getEventById",
     *     tags={"Events"},
     *     security={{"sunctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Event ID",
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
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorEventNotFound")
     *     ),
     * )
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $event = $this->eventsRepository->getById($id);
        if (!$event) {
            return response()->json(['message' => self::NOT_FOUND], ResponseStatus::NOT_FOUND);
        }
        return response()->json($event);
    }

    /**
     * @OA\Post(
     *     path="/events",
     *     summary="Create Event",
     *     description="Create a new Event",
     *     operationId="createEvent",
     *     tags={"Events"},
     *     security={{"sunctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/StoreEventRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Event"
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
     * @param StoreEventRequest $request
     * @return JsonResponse
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();
        $event = $this->eventsRepository->create($data);
        return response()->json($event, ResponseStatus::CREATED);
    }

    /**
     * @OA\Put(
     *     path="/events/{id}",
     *     summary="Update Event",
     *     description="Update an existing Event",
     *     operationId="updateEvent",
     *     tags={"Events"},
     *     security={{"sunctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Event to update",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             ref="#/components/schemas/UpdateEventRequest"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorEventNotFound")
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
     * @param UpdateEventRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventsRepository->getById($id);
        if (!$event) {
            return response()->json(['message' => self::NOT_FOUND], ResponseStatus::NOT_FOUND);
        }
        $data = $request->validated();
        $this->eventsRepository->update($event, $data);
        return response()->json($event);
    }

    /**
     * @OA\Delete(
     *     path="/events/{id}",
     *     summary="Delete Evet",
     *     description="Delete a Evet by ID",
     *     operationId="deleteEvet",
     *     tags={"Events"},
     *     security={{"sunctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the Event to delete",
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
     *         @OA\JsonContent(ref="#/components/schemas/ErrorEventNotFound")
     *     ),
     * )
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $event = $this->eventsRepository->getById($id);
        if (!$event) {
            return response()->json(['message' => self::NOT_FOUND], ResponseStatus::NOT_FOUND);
        }
        $this->eventsRepository->delete($event);
        return response()->json(null, ResponseStatus::NO_CONTENT);
    }
}
