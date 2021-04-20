<?php

namespace App\Repositories;

use App\Models\Action;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Cache;

class ScoreRepository
{
    protected $action;

    public function __construct(Action $actions)
    {
        $this->action = $actions;
    }

    public function allActions()
    {
        return $this->action::all();
    }

    public function updateAction($data)
    {
        $action = $this->action::find($data['id']);
        $action->score_correct = $data['score_correct'];
        $action->score_wrong = $data['score_wrong'];
        $action->save();

        return $action;
    }

    public function storeActivity($data)
    {
        $action = $this->action::where('title', $data['title'])->get()->first();

        if(UserActivity::where(['action_id' => $action->id, 'attendee_id' => $data['attendee_id']])->get()->first())
            return 'Data already entered!';

        $userActivity = new UserActivity();
        $userActivity->action_id = $action->id;
        $userActivity->attendee_id = $data['attendee_id'];
        $userActivity->save();

//        app('redis')->forget('rating');

        return 'Successfully set user activity!';
    }

    public function getRating()//TODO CACHE
    {
        try {
//            if($result = app('redis')->get('rating'))
//                return $result;

            $result = [];
            $usersActivities = UserActivity::all();
            foreach ($usersActivities as $activity) {
                $user = $activity->user()->toArray();
                if($user['company'] === 'Schneider Electric')
                    continue;
                $action = $activity->action();

                $result[$user['attendee_id']]['user'] = $user;
                if(isset($result[$user['attendee_id']]['score']))
                    $result[$user['attendee_id']]['score'] = $result[$user['attendee_id']]['score'] + $action->score_correct + $action->score_wrong;
                else
                    $result[$user['attendee_id']]['score'] = 0 + $action->score_correct + $action->score_wrong;
            }
            $result = array_slice($result,0,50);
            array_multisort(array_column($result, 'score'), SORT_DESC,  $result);

//            app('redis')->set('rating', $result);
        } catch (\Exception $e){
            throw new \InvalidArgumentException($e->getMessage());
        }
        return $result;
    }
}
