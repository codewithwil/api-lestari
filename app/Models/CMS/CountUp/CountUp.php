<?php

namespace App\Models\CMS\CountUp;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class CountUp extends Model
{
    use HasRandomId;

    protected $table = 'count_ups';
    protected $primaryKey = 'countUpId';
    protected $fillable = [
        'icon', 'title',
    ];

}
