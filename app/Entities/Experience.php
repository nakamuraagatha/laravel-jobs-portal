<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'notes', 'company', 'init', 'finish'];
    
    /**
     * Get the resume that owns the experience.
     */
    public function resume()
    {
        return $this->belongsTo('App\Entities\Resume');
    }
}
