<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table    = 'contacts';
    protected $fillable = ['user_id','first_name','last_name','email_address','phone_number','image'];
}
