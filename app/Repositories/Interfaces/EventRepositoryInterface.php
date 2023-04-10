<?php


namespace App\Repositories\Interfaces;


use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Event;

    public function create(array $attributes): Event;

    public function update(Event $event, array $attributes): bool;

    public function delete(Event $event): bool;
}
