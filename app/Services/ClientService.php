<?php

namespace App\Services;

use App\Repositories\ClientRepository;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var ClientRepository
     */
    protected $token, $token2;

    /**
     * ClientService constructor.
     * @param ClientRepository $clientRepository
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->token = $this->getToken();
//        $this->token2 = $this->getTokenV2();
    }

    /**
     * Get token from client
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getToken()
    {
        try {
            $result = $this->clientRepository->getToken();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get token');
        }

        return $result;
    }

    /**
     * Get token from client
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenV2()
    {
        try {
            $result = $this->clientRepository->getTokenV2();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get token');
        }

        return $result;
    }

    /**
     * Get users
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUsers()
    {
        try{
            $result = $this->clientRepository->getUsers($this->token);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get users');
        }

        return $result;
    }

    /**
     * Get Session list
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionList()
    {
        try{
            $result = $this->clientRepository->getSessionList($this->token);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get session list');
        }

        return $result;
    }

    /**
     * Get Session Personal list
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionPersonalList()
    {
        try{
            $result = $this->clientRepository->getSessionPersonalList($this->token);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get session personal list');
        }

        return $result;
    }

    /**
     * Get speakers
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSpeakers()
    {
        try{
            app('redis')->forget('speakers');
            $result = $this->clientRepository->getSpeakers($this->token);

            if(empty($result))
                throw new InvalidArgumentException('Speakers list is empty');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get speakers list');
        }

        return $result;
    }

    /**
     * Set User data
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setUserData($id)
    {
        try{
            $result = $this->clientRepository->getUserData($this->getTokenV2(), $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable set user data');
        }

        return $result;
    }

}
