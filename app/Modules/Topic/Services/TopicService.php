<?php

namespace App\Modules\Topic\Services;

use App\Models\Topic;
use App\Modules\Core\Services\Service;
use Illuminate\Support\Facades\DB;

class TopicService extends Service
{

    protected $_rules = [
        'name' => 'required|string|unique:topics,name',
    ];

    public function __construct(Topic $model)
    {
        parent::__construct($model);
    }

    public function all($filter = '', $limit = 6)
    {
        $data = $this->_model
            ->where('name', 'like', "%$filter%")
            ->limit($limit)
            ->withCount('ratables')
            ->get();

        return $data;
    }

    public function hot($limit = 6)
    {
        $data = $this->_model
            ->select('topics.*')
            ->join('ratables', 'topics.id', '=', 'ratables.topic_id')
            ->leftJoin('ratings', 'ratables.id', '=', 'ratings.ratable_id')
            ->groupBy('topics.id')
            ->orderByRaw('COUNT(ratings.id) DESC')
            ->limit($limit)
            ->get();

        return $data;
    }

    public function add($data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $userId = auth()->user()->id;

        $topic = new Topic();
        $topic->name = $data['name'];
        $topic->user_id = $userId;
        $topic->save();

        return $topic;
    }

    public function remove($id)
    {
        $user = auth()->user();
        $topic = $this->_model->find($id);

        if ($topic->user_id !== $user->id) {
            $this->addError('topic', 'You are not allowed to delete this topic');
            return;
        }

        return $this->_model->destroy($id);
    }
}