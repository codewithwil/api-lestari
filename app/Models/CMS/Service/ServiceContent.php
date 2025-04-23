<?php

namespace App\Models\CMS\Service;

use App\Traits\HasRandomId;
use Illuminate\Database\Eloquent\Model;

class ServiceContent extends Model
{
    use HasRandomId;

    protected $table = 'service_contents';
    protected $primaryKey = 'serviceContentId';
    protected $fillable = [
        'service_id', 'image', 'title', 'content', 'linkIcon', 
        'linkTitle', 'link'
    ];

    public function service(){return $this->belongsTo(Service::class, 'service_id', 'serviceId');}
    
}
