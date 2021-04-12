<?php

namespace App\Console\Commands;

use App\Models\Speaker;
use Illuminate\Console\Command;
use App\Services\ClientService;
use Illuminate\Support\Facades\DB;

class GetSpeakers extends Command
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
    protected $name = 'get:speakers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Get speakers from API Shnaider and input to DB";

    /**
     * Execute the console command.
     *
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        try {
            DB::table('speakers')->delete();
            $speakers = $this->clientService->getSpeakers();
            foreach ($speakers as $speaker) {
                Speaker::create([
                    'speakerid' => $speaker->speakerid,
                    'questionid' => $speaker->questionid,
                    'speaker_fname' => $speaker->speaker_fname,
                    'speaker_mname' => $speaker->speaker_mname,
                    'speaker_lname' => $speaker->speaker_lname,
                    'speaker_image' => $speaker->speaker_image
                ]);
            }
            echo 'Complete! Speakers list successfully added to Database.' . PHP_EOL;
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}
