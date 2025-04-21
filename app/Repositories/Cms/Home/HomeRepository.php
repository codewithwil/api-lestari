<?php

namespace App\Repositories\Cms\Home;

use App\DTOs\Cms\Home\HomeDTO;
use App\Models\CMS\Home\Home;
use App\Repositories\Cms\Home\HomeRepositoryInterface;

class HomeRepository implements HomeRepositoryInterface
{
    public function getAll()
    {
        return Home::with('buttons')->get();
    }

    public function find(string $homeId)
    {
        return Home::with('buttons')->findOrFail($homeId);
    }

    public function create(HomeDTO $data)
    {
        $home = Home::create([
            'image' => $data->image,
            'header' => $data->header,
            'description'  => $data->description,
        ]);

        foreach ($data->buttons as $button) {
            $home->buttons()->create($button);
        }

        return $home->load('buttons');
    }

    public function update(string $homeId, HomeDTO $data)
    {
        $home = Home::findOrFail($homeId);
        $home->update([
            'image' => $data->image,
            'header' => $data->header,
            'description'  => $data->description,
        ]);

        $home->buttons()->delete(); 
        foreach ($data->buttons as $button) {
            $home->buttons()->create($button);
        }

        return $home->load('buttons');
    }

    public function delete(string $homeId)
    {
        $home = Home::findOrFail($homeId);
        $home->delete();
    }
}
