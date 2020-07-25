<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    public $ratings = [1, 2, 3, 4, 5];
    protected $fillable = ['company_id', 'rating', 'amount_earned', 'duration', 'submitted_at'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function display_duration()
    {
        return formatTimestamp($this->duration);
    }

    public function display_amount_earned_in_peso()
    {
        return convertToPeso($this->amount_earned);
    }
}