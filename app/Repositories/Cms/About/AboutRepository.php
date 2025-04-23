<?php

namespace App\Repositories\Cms\About;

use App\{
    DTOs\Cms\About\AboutDTO,
    Models\CMS\About\About,
    Repositories\Cms\About\AboutRepositoryInterface,
};

class AboutRepository implements AboutRepositoryInterface
{
    public function getAll()
    {
        return About::get();
    }

    public function find(string $aboutId)
    {
        return About::findOrFail($aboutId);
    }

    public function create(AboutDTO $data)
    {
        $about = About::create([
            'image' => $data->image,
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);


        return $about;
    }

    public function update(string $aboutId, AboutDTO $data)
    {
        $about = About::findOrFail($aboutId);
        $about->update([
            'image' => $data->image,
            'header' => $data->header,
            'desc'  => $data->desc,
        ]);

        return $about;
    }

    public function delete(string $aboutId)
    {
        $about = About::findOrFail($aboutId);
        $about->delete();
    }
}
