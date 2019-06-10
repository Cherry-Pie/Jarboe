<?php

namespace Yaro\Jarboe\Events\Auth;

use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;

class LoginFailed
{
    use SerializesModels;

    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new event instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get request object.
     *
     * @return Request
     */
    public function request()
    {
        return $this->request;
    }
}
