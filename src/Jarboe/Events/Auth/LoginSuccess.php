<?php

namespace Yaro\Jarboe\Events\Auth;

use Illuminate\Queue\SerializesModels;

class LoginSuccess
{
    use SerializesModels;

    private $admin;

    /**
     * Create a new event instance.
     *
     * @param $admin
     */
    public function __construct($admin)
    {
        $this->admin = $admin;
    }

    /**
     * Get admin model.
     *
     * @return mixed
     */
    public function admin()
    {
        return $this->admin;
    }
}
