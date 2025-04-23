<?php

namespace App\Models\CMS\Service;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasRandomId;

    protected $table = 'services';
    protected $primaryKey = 'serviceId';
    protected $fillable = [
        'header', 'desc'
    ];

    public function serviceContent(){return $this->hasMany(ServiceContent::class, 'service_id', 'serviceId');}
    
}
