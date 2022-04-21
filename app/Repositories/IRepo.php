<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface IRepo
{
    public function model(): object;

    public function all(): Collection;

    public function create(array $data): object;

    public function update(int $id, array $data): bool;

    public function findById(int $id): object|null;

    public function destroy(int $id): bool;
}
