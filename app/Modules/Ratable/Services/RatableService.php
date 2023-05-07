<?php

namespace App\Modules\Ratable\Services;

use App\Models\Ratable;
use App\Models\RatableLanguage;
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
                ->paginate($pages)
                ->withQueryString();
        } else {
            $data = $this->_model
                ->where("topic_id", $topicId)
                ->with("ratableLanguage")
                ->paginate($pages)
                ->withQueryString();
        }

        return $data;
    }

    public function add($topicId, $data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
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
                ->find($id);
        } else {
            $data = $this->_model
                ->where("topic_id", $topicId)
                ->with("ratableLanguage")
                ->find($id);
        }

        return $data;
    }

    public function update($topicId, $id, $data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
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
        $model = $this->_model
            ->where("topic_id", $topicId)
            ->where("id", $id)
            ->first();
        
        $model->ratableLanguage()->delete();

        return $model->delete();
    }
}