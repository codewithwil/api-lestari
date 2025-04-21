<?php

namespace App\Repositories\Cms\About;

use App\DTOs\Cms\About\AboutDto;

interface AboutRepositoryInterface
{
    public function getAll();
    public function find(string $aboutId);
    public function create(AboutDto $data);
    public function update(string $aboutId, AboutDto $data);
    public function delete(string $aboutId);
}
