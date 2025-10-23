<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diploma extends Model
{
    protected $fillable = [
        'firstname', 'lastname', 'score', 'document_number', 'subject_id'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
