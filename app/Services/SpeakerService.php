<?php
namespace App\Services;

use App\Repositories\SpeakerRepository;
use InvalidArgumentException;
use Illuminate\Support\Facades\Log;

class SpeakerService
{
    protected $speakerRepository;

    public function __construct(SpeakerRepository $speakerRepository)
    {
        $this->speakerRepository = $speakerRepository;
    }

    public function getSpeakers()
    {
        try {
//            if($result = app('redis')->get('speakers'))
//                return $result;
            $result = $this->speakerRepository->getSpeakers();
            foreach ($result as &$speaker) {
                $speaker['name'] = $speaker['speaker_fname'].' '.$speaker['speaker_lname'];
                $speaker['position'] = $speaker['speaker_titles'];
                $speaker['company'] = $speaker['speaker_companies'];
                $speaker['photo'] = $speaker['speaker_image'];
                unset($speaker['speaker_lname'],
                    $speaker['speaker_fname'],
                    $speaker['speaker_mname'],
                    $speaker['questionid'],
                    $speaker['speaker_titles'],
                    $speaker['speaker_companies'],
                    $speaker['speaker_image']);
            }
//            app('redis')->set('speakers', $result);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new InvalidArgumentException('Unable get speakers');
        }

        return $result;
    }
}
