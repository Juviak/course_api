<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class capability extends Model
{
    use HasFactory;

    protected $fillable = ['capabilityName'];
    public $timestamps = false;
    
    public function courses(){

        return $this->belongsTo(course::class);
    }

  
   public function skills()
   {
       return $this->hasMany(skill::class,'capabilityId');
   }

}
