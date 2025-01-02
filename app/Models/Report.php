<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['pdf_template_id', 'client_id','file_path','score', 'website_image', 'generated_at'];
}
