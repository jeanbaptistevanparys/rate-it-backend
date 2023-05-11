<?php

namespace App\Modules\Ratable\Services;

use App\Models\Ratable;
use App\Models\RatableLanguage;
use App\Models\Topic;
use App\Modules\Core\Services\Service;

class RatableService extends Service
{

    protected $_rules = [
        "image" => "required|string",
        'translations' => 'required|array',
        'translations.*.language' => 'required|alpha|min:2|max:2',
        'translations.*.name' => 'required|string',
        'translations.*.description' => 'required|string',
    ];

    public function __construct(Ratable $model)
    {
        parent::__construct($model);
    }

    public function all($topicId, $pages = 10, $language = null)
    {
        if ($language) {
            $data = $this->_model
                ->where("topic_id", $topicId)
                ->with(["ratableLanguage" => function ($query) use ($language) {
                    $query->where("language", $language);
                }])
                ->withCount('ratings')
                ->paginate($pages)
                ->withQueryString();
        } else {
            $data = $this->_model
                ->where("topic_id", $topicId)
                ->with("ratableLanguage")
                ->withCount('ratings')
                ->paginate($pages)
                ->withQueryString();
        }

        $data->getCollection()->transform(function ($item) {
            $ratingCount = $item->ratings_count;
            $totalScore = $item->ratings->sum('score');
            $averageScore = $ratingCount > 0 ? $totalScore / $ratingCount : 0;
    
            $item['average_score'] = $averageScore;
    
            return $item;
        });


        return $data;
    }

    public function add($topicId, $data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $userId = auth()->user()->id;
        $topic = Topic::find($topicId);
        if ($topic->user_id !== $userId) {
            $this->addError('topic', 'You are not allowed to add a ratable to this topic');
            return;
        }

        $ratable = new Ratable();
        $ratable->topic_id = $topicId;
        $ratable->image = $data['image'];
        $ratable->save();

        foreach ($data['translations'] as $language) {
            $ratableLanguage = new RatableLanguage();
            $ratableLanguage->ratable_id = $ratable->id;
            $ratableLanguage->language = $language['language'];
            $ratableLanguage->name = $language['name'];
            $ratableLanguage->description = $language['description'];
            $ratableLanguage->save();
        }

        return $ratable;
    }

    public function find($topicId, $id, $language = null)
    {
        if ($language) {
            $data = $this->_model
                ->where("topic_id", $topicId)
                ->with(["ratableLanguage" => function ($query) use ($language) {
                    $query->where("language", $language);
                }])
                ->withCount("ratings")
                ->find($id);
        } else {
            $data = $this->_model
                ->where("topic_id", $topicId)
                ->with("ratableLanguage")
                ->withCount("ratings")
                ->find($id);
        }

        $data->average_score = $data->ratings_count > 0 ? $data->ratings->sum('score') / $data->ratings_count : 0;


        return $data;
    }

    public function update($topicId, $id, $data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $userId = auth()->user()->id;
        $topic = Topic::find($topicId);
        if ($topic->user_id !== $userId) {
            $this->addError('topic', 'You are not allowed to update a ratable to this topic');
            return;
        }

        $ratable = $this->_model
            ->where("topic_id", $topicId)
            ->where("id", $id)
            ->first();

        $ratable->image = $data['image'];
        $ratable->save();

        foreach ($data['translations'] as $language) {
            $ratableLanguage = $ratable->ratableLanguage()->where('language', $language['language'])->first();

            $ratableLanguage->name = $language['name'];
            $ratableLanguage->description = $language['description'];
            $ratableLanguage->save();
        }

        return $ratable;
    }

    public function remove($topicId, $id)
    {
        $userId = auth()->user()->id;
        $topic = Topic::find($topicId);
        if ($topic->user_id !== $userId) {
            $this->addError('topic', 'You are not allowed to delete a ratable to this topic');
            return;
        }

        $model = $this->_model
            ->where("topic_id", $topicId)
            ->where("id", $id)
            ->first();

        $model->ratableLanguage()->delete();

        return $model->delete();
    }

}
