<?php
namespace App\Repositories;

use App\Models\Session;

class SessionRepository
{
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getSessions()
    {
        return $this->session::all();
    }
}
