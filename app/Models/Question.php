<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question_category_id', 'text'];

    public function category()
    {
        return $this->belongsTo(QuesitonCategory::class, 'question_category_id');
    }
}
