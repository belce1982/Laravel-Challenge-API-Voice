<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voice extends Model
{
    use HasFactory;

    protected $fillable =[
        'value',
        'user_id',
        'question_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }    
}
