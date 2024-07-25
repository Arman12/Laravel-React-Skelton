<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function find(int $id)
    {
        return $this->model->find($id);
    } 
    
    public function findAll()
    {
        return $this->model->all();
    }
    public function findAllWhere(array $where)
    {
        return $this->model->where($where)->get();
    }

    public function update(int $id, array $data)
    {
        $model = $this->find($id);
        if ($model) {
            $model->update($data);
            return $model;
        }
        return null;
    }

    public function delete(int $id)
    {
        $model = $this->find($id);
        if ($model) {
            $model->delete();
            return true;
        }
        return false;
    }

    // Add more methods as needed...
}
