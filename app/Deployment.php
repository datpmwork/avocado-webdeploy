<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deployment extends Model
{
    protected $table = "deploys";
    
    protected $relations = ['website'];
    
    # Relations
    public function website() {
        return $this->belongsTo(Website::class, 'website_id');
    }
}
