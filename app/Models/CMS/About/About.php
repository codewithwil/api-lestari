<?php

namespace App\Models\CMS\About;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasRandomId;

    protected $table = 'abouts';
    protected $primaryKey = 'aboutId';
    protected $fillable = [
        'image', 'header', 'desc'
    ];

}
