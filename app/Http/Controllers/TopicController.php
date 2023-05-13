<?php

namespace App\Http\Controllers;

use App\Modules\Topic\Services\TopicService;
use Illuminate\Http\Request;

class TopicController extends Controller
{

    private $_service;
    public function __construct(TopicService $service)
    {
        $this->_service = $service;
    }

    public function all(Request $request)
    {
        $limit = $request->get('limit', 6);
        $filter = $request->get('filter', '');
        return $this->_service->all($filter, $limit);
    }

    public function hot(Request $request)
    {
        $limit = $request->get('limit', 6);
        return $this->_service->hot($limit);
    }

    public function add(Request $request)
    {
        $data = $request->all();
        $topic = $this->_service->add($data);

        if ($this->_service->hasErrors()) {
            return ["errors" => $this->_service->getErrors()];
        }

        return $topic;
    }

    public function remove($id)
    {
        $this->_service->remove($id);

        if ($this->_service->hasErrors()) {
            return ["errors" => $this->_service->getErrors()];
        }

        return ["message" => "Topic removed"];
    }
}
