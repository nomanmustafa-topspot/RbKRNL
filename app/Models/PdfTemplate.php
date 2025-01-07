<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfTemplate extends Model
{
    protected $table = 'pdf_templates';
    protected $fillable = ['name','version'] ;

    public function factors()
    {
        return $this->hasMany(Question::class);
    }
}
