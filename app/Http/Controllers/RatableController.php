<?php

namespace App\Http\Controllers;

use App\Modules\Ratable\Services\RatableService;
use Illuminate\Http\Request;

class RatableController extends Controller
{

    private $_service;
    public function __construct(RatableService $service)
    {
        $this->_service = $service;
    }

    public function all($topicId, Request $request)
    {
        $filter = $request->get("filter", '');
        $pages = $request->get("pages", 6);
        $language = $request->get("language", 'en');

        return $this->_service->all($topicId, $pages, $language, $filter);
    }

    public function add($topicId, Request $request)
    {
        $data = $request->all();
        $ratable = $this->_service->add($topicId, $data);

        if ($this->_service->hasErrors()) {
            return ["errors" => $this->_service->getErrors()];
        }

        return $ratable;
    }

    public function find($topicId, $id, Request $request)
    {
        $language = $request->get("language", null);

        return $this->_service->find($topicId, $id, $language);
    }

    public function update($topicId, $id, Request $request)
    {
        $data = $request->all();
        $ratable = $this->_service->update($topicId, $id, $data);

        if ($this->_service->hasErrors()) {
            return ["errors" => $this->_service->getErrors()];
        }

        return $ratable;
    }

    public function remove($topicId, $id)
    {
        $this->_service->remove($topicId, $id);

        if ($this->_service->hasErrors()) {
            return ["errors" => $this->_service->getErrors()];
        }

        return ["message" => "Ratable removed"];
    }
}
