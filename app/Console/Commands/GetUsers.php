<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;

class GetUsers extends Command
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
    protected $name = 'get:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get users from API Shnaider and input to DB";

    /**
     * Execute the console command.
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            DB::table('users')->delete();

            $users = $this->clientService->getUsers();

            if(empty($users))
                return 'Users is empty!';

            foreach ($users as $user) {
                User::create([
                    'fname' => $user->fname,
                    'mname' => $user->mname,
                    'lname' => $user->lname,
                    'mphone' => $user->mphone,
                    'city' => $user->city,
                    'company' => $user->company,
                    'email' => $user->email,
                    'attendee_id' => $user->attendeeid,
                    'password' => app('hash')->make($user->attendeeid)
                ]);
            }
            return 'Complete! Users successfully added to Database.' . PHP_EOL;
        } catch (\Exception $e) {
            return $e->getMessage() . PHP_EOL;
        }
    }
}
