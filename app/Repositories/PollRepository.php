<?php

namespace App\Repositories;

use App\Models\Location;
use App\Models\Poll;
use App\Models\PollResult;
use Illuminate\Support\Facades\DB;

class PollRepository
{
    protected $pollResult,$poll;

    /**
     * PollRepository constructor.
     * @param $pollResult
     */
    public function __construct(PollResult $pollResult, Poll $poll)
    {
        $this->pollResult = $pollResult;
        $this->poll = $poll;
    }

    public function allPolls()
    {
        return $this->poll::all();
    }

    /**
     * @param $poll_id
     * @return mixed
     */
    public function getPollResult($poll_id)
    {
        $pollResults = $this->pollResult::where('poll_id', $poll_id)->get();
        return $pollResults;
    }

    /**
     * @param $poll_id
     * @return \Illuminate\Support\Collection
     */
    public function getPollResultBefore($poll_id)
    {
        date_default_timezone_set('Europe/Moscow');
        $pollResultBefore = DB::table('user_answers')
            ->where('poll_id', 10)
            ->whereTime('time', '<=', '2021-04-29 17:30:00')
            ->get();
//        dd($pollResultBefore);
        return $pollResultBefore;
    }

    /**
     * @param $poll_id
     * @return \Illuminate\Support\Collection
     */
    public function getPollResultAfter($poll_id)
    {
        date_default_timezone_set('Europe/Moscow');
        $pollResultAfter = DB::table('user_answers')
            ->where('poll_id', $poll_id)
            ->whereTime('time', '>', '2021-04-29 17:30:00')
            ->get();
//        dd($pollResultAfter);
        return $pollResultAfter;
    }

    /**
     * @param $data
     * @return PollResult|null
     */
    public function storeUserAnswer($data)
    {
        try {
            $pollResult = new PollResult();
            $pollResult->user_id = 219493737;//auth()->user()->attendee_id;
            $pollResult->answer_id = $data['answer_id'];
            $pollResult->poll_id = $data['poll_id'];
            $pollResult->save();

            $result = $pollResult->fresh();
        } catch (\Exception $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
        return $result;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function storeUserEvaluation($data)
    {
        try {
            $location_name = Location::where('stream_id', $data['stream_id'])->get()->first()->name;
            $user = auth()->user();

            $client = new \Google_Client();
            $client->setApplicationName("shnaider");
            $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
            $client->setAuthConfig(base_path() . '/public/MyProject-098ec42a6a12.json');
            $client->setAccessToken("098ec42a6a1281836f35611ccdd30f85948dafe6");

            $service = new \Google_Service_Sheets($client);

            $options = array('valueInputOption' => 'RAW');
            $values = [[date("d.m.y H:i:s"), $data['rating'], $data['text'], $user->attendee_id, $user->fname, $user->lname, $user->email, $user->mphone]];
            $body = new \Google_Service_Sheets_ValueRange(['values' => $values]);

            $result = $service->spreadsheets_values->append("1E5BC5vw4TsrPe3jtXrf7NqzGncL5nnBCJX03-NCzX38", $location_name.'!A1:H1', $body, $options);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }

        return $result;
    }

    /**
     * @param $poll_id
     * @return bool
     */
    public function isQuiz($poll_id)
    {
        $isQuiz = false;

        Poll::find($poll_id)->answers->each(function ($element) use (&$isQuiz){
            if ((int) $element->true_answer === 1) {
                $isQuiz = !$isQuiz;
                return false;
            }
        });

        return (bool) $isQuiz;
    }
}
