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

    public function all()
    {
        return $this->_service->all();
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

    public function delete($id)
    {
        return $this->_service->delete($id);
    }
}
