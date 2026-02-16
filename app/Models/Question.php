<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
     protected $fillable = [
        'school_id','class','subject','question_text','marks','type','status', 'passage', 'passage_id', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option','difficulty','created_by'
    ];

   
}
