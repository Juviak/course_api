<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;

    
    protected $fillable = ['courseName', 'startDate', 'endDate', 'courseImage'];

    public function capabilities(){

        return $this->hasMany(capability::class,'courseId');
    }
}
