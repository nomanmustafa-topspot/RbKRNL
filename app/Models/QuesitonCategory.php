<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuesitonCategory extends Model
{
    protected $table = 'question_categories';
    protected $fillable = ['name'];
}
