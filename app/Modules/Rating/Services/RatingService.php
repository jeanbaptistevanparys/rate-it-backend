<?php

namespace App\Modules\Rating\Services;

use App\Models\Ratable;
use App\Models\Rating;
use App\Modules\Core\Services\Service;

class RatingService extends Service 
{

    public function __construct(Rating $model)
    {
        parent::__construct($model);
    }

    public function find($topicId, $ratableId) {
        $user = auth()->user();

        $rating = $this->_model->where('ratable_id', $ratableId)->where('user_id', $user->id)->first();

        return $rating;
    }

    public function add($topicId, $ratableId, $data)
    {
        $userId = auth()->user()->id;

        $rating = new Rating();
        $rating->ratable_id = $ratableId;
        $rating->score = $data['score'];
        $rating->user_id = $userId;
        $rating->save();

        return $rating;
    }

    public function remove($topicId, $ratableId, $id)
    {
        $userId = auth()->user()->id;
        $rating = $this->_model->where('ratable_id', $ratableId)->where('id', $id)->first();

        if ($rating->user_id !== $userId) {
            $this->addError('ratable', 'You are not allowed to delete this rating');
            return;
        }

        $rating->delete();

        return $rating;
    }
}