<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    protected $fillable = ['pdf_template_id', 'factor','type','value', 'result'];
    public function pdfTemplate()
    {
        return $this->belongsTo(PdfTemplate::class);
    }
}
