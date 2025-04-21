<?php

namespace App\Models\CMS\Home;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasRandomId;

    protected $table = 'homes';
    protected $primaryKey = 'homeId';
    protected $fillable = [
        'image', 'header', 'description'
    ];

    public function buttons()
    {
        return $this->hasMany(HomeButtons::class, 'home_id');
    }

}
