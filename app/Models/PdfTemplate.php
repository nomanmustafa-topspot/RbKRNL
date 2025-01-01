<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PdfTemplate extends Model
{
    protected $fillable = ['version', 'name'];
    public function factors()
    {
        return $this->hasMany(Factor::class);
    }
}
