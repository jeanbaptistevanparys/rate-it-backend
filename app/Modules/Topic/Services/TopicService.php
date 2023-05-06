<?php

namespace App\Modules\Topic\Services;

use App\Models\Topic;
use App\Modules\Core\Services\Service;

class TopicService extends Service
{

    protected $_rules = [
        'name' => 'required|string|unique:topics,name',
    ];

    public function __construct(Topic $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        return $this->_model->all();
    }

    public function add($data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $topic = new Topic(); // no mass assignment with name being primary
        $topic->name = $data['name'];
        $topic->save();

        return $topic;
    }

    public function delete($id)
    {
        return $this->_model->destroy($id);
    }
}