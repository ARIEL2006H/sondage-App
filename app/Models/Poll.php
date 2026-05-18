<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    public function questions() { return $this->hasMany(Question::class); }
protected $fillable = ['title', 'description'];

}
