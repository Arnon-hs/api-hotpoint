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
     * Get token V2
     * @return null|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTokenV2()
    {
        try{
            $response = $this->client->request('POST', 'https://eu.eventscloud.com/api/v2/global/authorize.json',[
                'form_params' => [
                    'accountid' => env('ACCOUNT_ID'),
                    'key' => env('API_KEY')
                ]
            ]);
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
            $speakers = json_decode($response->getBody())->ResultSet;
        }catch (\Exception $e) {
            $speakers = null;
        }

        return $speakers;
    }

    /**
     * Get User data
     * @param $token
     * @param $id
     * @return array|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getUserData($token, $id)
    {
//        try{//GET https://[API Endpoint Host]/api/v2/ereg/getAttendee.json
            $response = $this->client->request('GET', 'https://eu.eventscloud.com/api/v2/ereg/getAttendee.json?accesstoken='.$token.'&attendeeid='.$id.'&eventid='.env('EVENT_ID').'&responseArrray=1');
//            $result = json_decode($response->getBody())->ResultSet;
            dd($response->getBody(), $id, $token, $response);
//        }catch (\Exception $e) {
//            $result = null;
//        }

        return $result;
    }
}
