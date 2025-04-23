<?php

namespace App\Repositories\Cms\Service;

use App\DTOs\Cms\Service\ServiceDto;

interface ServiceRepositoryInterface
{
    public function getAll();
    public function find(string $serviceId);
    public function create(ServiceDto $data);
    public function update(string $serviceId, ServiceDto $data);
    public function delete(string $serviceId);
}
