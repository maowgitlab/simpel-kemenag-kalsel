<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceApplicant extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function list()
    {
        return $this->belongsTo(ListService::class, 'list_id');
    }
}
