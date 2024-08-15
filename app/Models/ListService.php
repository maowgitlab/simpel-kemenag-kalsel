<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListService extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function services()
    {
        return $this->hasMany(Service::class, 'list_id');
    }

    public function applicants()
    {
        return $this->hasMany(ServiceApplicant::class, 'list_id');
    }
}
