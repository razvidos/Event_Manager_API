<?php


namespace App\Repositories\Interfaces;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?User;

    public function create(array $attributes): User;

    public function update(User $user, array $attributes): bool;

    public function delete(User $user): bool;
}
