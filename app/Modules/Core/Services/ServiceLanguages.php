<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\Validator;

abstract class ServiceLanguages extends Service
{

    protected $_rulesTranslations = [];

    protected function validate($data)
    {
        
        $translationData = isset($data["translations"]) ? $data["translations"] : null;
        unset($data["translations"]);

        $this->validateData($data);

        if ($translationData != null) {
            $this->validateDataTranslations($translationData);
        }
    }

    private function validateData($data, $rules = null)
    {
        if ($rules == null) {
            $rules = $this->_rules;
        }

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            $this->_errors->merge($validator->errors());
            return;
        }
    }

    private function validateDataTranslations($data)
    {
        foreach ($data as $translation) {
            $this->validateDataTranslation($translation);
        }
    }

    private function validateDataTranslation($data)
    {
        return $this->validateData($data, $this->_rulesTranslations);
    }

    protected function presentAllWithTranslations($data)
    {
        foreach ($data["data"] as $record) {
            $data["data"] = $this->presentFindWithTranslations($record);
        }
        return $data;
    }

    protected function presentFindWithTranslations($data)
    {
        if (!isset($data["translations"])) {
            return $data;
        }

        $translations = [];
        foreach ($data["translations"] as  $translation) {
            $translations[$translation["language"]] = $translation;
        }
        $data["translations"] = $translations;

        return $data;
    }

    protected function presentDetailWithTranslations($data)
    {
        return (count($data["translations"])) ? $data["translations"][0] : null;
    }

    protected function presentListWithTranslations($data)
    {
        foreach ($data["data"] as $index => $record) {
            $data["data"][$index]["translations"] = $this->presentDetailWithTranslations($record);
        }

        return $data;
    }
}
