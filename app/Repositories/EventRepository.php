<?php


namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function getAll(): Collection
    {
        return Event::all();
    }

    public function getById(int $id): ?Event
    {
        return Event::find($id);
    }

    public function create(array $attributes): Event
    {
        return Event::create($attributes);
    }

    public function update(Event $event, array $attributes): bool
    {
        return $event->update($attributes);
    }

    public function delete(Event $event): bool
    {
        return $event->delete();
    }
}
