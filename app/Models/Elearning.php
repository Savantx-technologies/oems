<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elearning extends Model
{
    protected $fillable = [
        'school_id',
        'class_id',
        'topic',
        'sub_topic',
        'content',
        'pdf_file',
        'video_link',
        'video_file'
    ];

    public function class()
    {
        return $this->belongsTo(User::class,'class_id');
    }
}
