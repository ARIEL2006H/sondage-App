<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'poll_id'];
    public function options() { return $this->hasMany(Option::class); }
public function poll() { return $this->belongsTo(Poll::class); }
}
