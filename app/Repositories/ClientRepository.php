<?php

namespace App\Repositories;

use GuzzleHttp\Client;

class ClientRepository
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * ClientRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get token
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getToken()
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/authenticate?accountid='.env('ACCOUNT_ID').'&key=' . env('API_KEY'));
            $token = json_decode($response->getBody())->accesstoken;
        }catch (\Exception $e) {
            $token = null;
        }

        return $token;
    }

    /**
     * Get users
     * @param $token
     * @return null|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUsers($token)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/attendeelist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token);
            $users = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            $users = null;
        }

        return $users;
    }

    /**
     * Get Session List
     * @param $token
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSessionList($token)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/sessionlist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token);
            $sessionList = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            $sessionList = null;
        }

        return $sessionList;
    }

    /**
     * Get Session List
     * @param $token
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSpeakers($token)
    {
        try{
            $response = $this->client->request('GET', 'https://api-emea.eventscloud.com/api/ds/v1/speakerlist/'.env('ACCOUNT_ID').'/'.env('EVENT_ID').'?accesstoken='.$token);
            $speakers = json_decode($response->getBody())->ResultSet;//
//            dd($speakers);

        }catch (\Exception $e) {
            $speakers = null;
        }

        return $speakers;
    }
}
