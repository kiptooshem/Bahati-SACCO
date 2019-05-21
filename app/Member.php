<?php

namespace BahatiSACCO;

use BahatiSACCO\Events\MemberCreated;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{
    use Notifiable;
    //
    protected $dispatchesEvents = [
        'created'=> MemberCreated::class
    ];

    protected $table = "members";

    protected $fillable = [
        'name','email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    function vehicles(){
        return $this->hasMany(Vehicle::class, "owner_id", "id");
    }
}
