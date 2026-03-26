<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    public function __construct(protected Model $model)
    {
    }

    public function all()
    {
        return $this->model->newQuery()->get();
    }

    public function find(int $id)
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data)
    {
        return $this->model->newQuery()->create($data);
    }
}
