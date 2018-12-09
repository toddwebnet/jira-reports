<?php

namespace App\Services;


use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModelService
{
    public function saveToDb($modelClass, $conditions, $data)
    {

        try {
            $table = ($modelClass)::where($conditions)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $table = new $modelClass();

        }
        foreach ($data as $key => $value) {
            $table->{$key} = $value;
        }

        $table->save();
        return $table;
    }
}