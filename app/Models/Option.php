<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    
    // Relation pour compter les votes reçus par cette option
   
    public function votes() {
    return $this->hasMany(Vote::class);
}

protected $fillable = [
    'label', 
    'question_id', 
    'is_correct', 
    'votes_count'
];

public function question() {
    return $this->belongsTo(Question::class);
}
}