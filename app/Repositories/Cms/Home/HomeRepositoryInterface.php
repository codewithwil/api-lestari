<?php

namespace App\Repositories\Cms\Home;

use App\DTOs\Cms\Home\HomeDTO;
use Illuminate\Http\Request;

interface HomeRepositoryInterface
{
    public function getAll();
    public function find(string $homeId);
    public function create(HomeDTO $data);
    public function update(string $homeId, HomeDTO $data);
    public function delete(string $homeId);
}
