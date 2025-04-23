<?php

namespace App\Repositories\Cms\CountUp;

use App\DTOs\Cms\CountUp\CountUpDto;

interface CountUpRepositoryInterface
{
    public function getAll();
    public function find(string $countUpId);
    public function create(CountUpDto $data);
    public function update(string $countUpId, CountUpDto $data);
    public function delete(string $countUpId);
}
