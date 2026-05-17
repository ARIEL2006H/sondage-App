<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class poll extends Model
{
    public function questions() { return $this->hasMany(Question::class); }
protected $fillable = ['title', 'description'];

}
