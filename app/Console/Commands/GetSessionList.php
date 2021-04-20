<?php

namespace App\Console\Commands;

use App\Models\Session;
use Illuminate\Console\Command;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;

class GetSessionList extends Command
{
    /**
     * @var ClientService
     */
    protected $clientService;

    /**
     * Create a new command instance.
     *
     * @var ClientService
     * @return void
     */
    public function __construct(ClientService $clientService)
    {
        parent::__construct();
        $this->clientService = $clientService;
    }

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'get:sessionlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get session list from API Shnaider and input to DB";

    /**
     * Execute the console command.
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            DB::table('session_list')->delete();

            $sessionList = $this->clientService->getSessionList();
            if(empty($sessionList))
                echo 'Session list is empty';
            dd($sessionList);
            foreach ($sessionList as $session) {
                Session::create([
                    'session_id' => $session->session_id,
                    'speaker_id' => $session->speaker_id,
                    'sort' => $session->sort,
                    'name' => $session->name,
                    'sessiondate' => $session->sessiondate,
                    'starttime' => $session->starttime,
                    'endtime' => $session->endtime,
                    'location_id' => $session->location_id,
                ]);
            }
            echo 'Complete! Session list successfully added to Database.' . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
