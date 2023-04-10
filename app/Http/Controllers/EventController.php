<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\ResponseStatus;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;

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
