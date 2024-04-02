<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skill extends Model
{
    use HasFactory;
    
    protected $fillable = ['skillName'];
    public $timestamps = false;
    
    public function capability(){

        return $this->belongsTo(capability::class); 
    }
}


