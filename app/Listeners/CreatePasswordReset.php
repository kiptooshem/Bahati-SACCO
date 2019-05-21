<?php

namespace BahatiSACCO\Listeners;

use BahatiSACCO\Events\ConductorCreated;
use BahatiSACCO\Jobs\MailJob;
use BahatiSACCO\MailModel;
use BahatiSACCO\PasswordReset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mockery\Generator\StringManipulation\Pass\Pass;

class CreatePasswordReset
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ConductorCreated  $event
     * @return void
     */
    public function handle(ConductorCreated $event)
    {
        $user = $event->user;
        $role = $event->role;
        $access_hash = md5($user->email.time());

        $data = [
            "email"=> $user->email,
            "role"=> $role,
            "access_hash"=> $access_hash
        ];

        PasswordReset::create($data);


        // EMAIL begin
        $name = $user->name;
        $email = $user->email;
        $subject = "Setup Your Account Password";
        $body = "Follow the link below to setup your account password: \n ". url('reset-password/'.$access_hash);

        $email_send = new MailModel($name, $email, $subject, $body);

        dispatch(new MailJob(serialize($email_send)));
    }
}
