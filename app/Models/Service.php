<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function list()
    {
        return $this->belongsTo(ListService::class, 'list_id');
    }

    public function applicants()
    {
        return $this->hasMany(ServiceApplicant::class, 'service_id');
    }
}
