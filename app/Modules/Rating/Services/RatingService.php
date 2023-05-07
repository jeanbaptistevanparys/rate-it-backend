<?php

namespace App\Modules\Rating\Services;

use App\Models\Rating;
use App\Modules\Core\Services\Service;

class RatingService extends Service 
{

    public function __construct(Rating $model)
    {
        parent::__construct($model);
    }

    public function all($topicId, $ratableId) {
        return $this->_model->where('ratable_id', $ratableId)->get();
    }

    public function add($topicId, $ratableId) {
        $rating = new Rating();
        $rating->topic_id = $topicId;
        $rating->ratable_id = $ratableId;
        $rating->save();

        return $rating;
    }

    public function remove($topicId, $ratableId, $id) {
        $rating = $this->_model->where('ratable_id', $ratableId)->where('id', $id)->first();
        $rating->delete();

        return $rating;
    }
}