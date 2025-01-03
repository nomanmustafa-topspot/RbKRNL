<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientAnswer extends Model
{
    protected $fillable = ['question_id', 'client_id','value','result'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
