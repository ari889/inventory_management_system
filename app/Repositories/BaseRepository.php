<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class BaseRepository{
    protected $model;
    protected $column_order;
    protected $orderValue;
    protected $dirValue;
    protected $startVlaue;
    protected $lengthVlaue;

    protected function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @param $attributes(array)
     * @return mixed
     *
     * Create database row
     */
    public function create(array $attributes){
        return $this->model->create($attributes);
    }

    /**
     * @param $attributes(array)
     * @return mixed
     *
     * Create database row
     */
    public function insert(array $attributes){
        return $this->model->insert($attributes);
    }

    /**
     * @param $attributes(array)
     * @param $id(integer)
     *
     * @return mixed
     *
     * Update database row
     */
    public function update(array $attributes, int $id) : bool
    {
        return $this->model->find($id)->update($attributes);
    }

    /**
     * @param $attributes(array)
     * @param $search_data(array)
     *
     * @return mixed
     *
     * Update or create database row
     */
    public function updateOrCreate(array $search_data, array $attributes){
        return $this->model->updateOrCreate($search_data, $attributes);
    }


    /**
     * @param $attributes(array)
     * @param $search_data(array)
     *
     * @return mixed
     *
     * Update or Insert database row
     */
    public function updateOrInsert(array $search_data, array $attributes){
        return $this->model->updateOrInsert($search_data, $attributes);
    }


    /**
     * @param $columns(default all column)
     * @param $orderby(column name)
     * @param $sort(ASC, DESC)
     *
     * get all data
     */
    public function all($columns = array('*'), string $orderby='id', string $sortby = 'desc'){
        return $this->model->orderBy($orderby, $sortby)->get($columns);
    }


    /**
     * @param $id(integer)
     * find using id
     */
    public function find(int $id){
        return $this->model->find($id);
    }


    /**
     * @param $id(integer)
     * find using id
     *
     * @return mixed
     * @throws ModelNotFountException
     */
    public function findOrFail(int $id){
        return $this->model->findOrFail($id);
    }


    /**
     * @param $data(array)
     * @return mixed
     *
     * find by column name
     */
    public function findBy(array $data){
        return $this->model->where($data)->get();
    }

    /**
     * @param $data(array)
     * @return mixed
     *
     * find first data
     */
    public function findOneBy(array $data){
        return $this->model->where($data)->first();
    }

    /**
     * @param $data(array)
     * @return mixed
     * @throws ModelNotFountException
     *
     * find or fail
     */
    public function findOneByFail(array $data){
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * @param $id(integer)
     * @return boolean
     *
     * delete data
     */
    public function delete($id) : bool
    {
        return $this->model->find($id)->delete();
    }


    /**
     * @param $data(array)
     * @return boolean
     *
     * delet multiple data
     */
    public function destroy(array $data) : bool
    {
        return $this->model->destroy($data);
    }

    /**
     * data table default value set method
     */
    public function setOrderValue($orderValue)
    {
        $this->orderValue = $orderValue;
    }
    public function setDirValue($dirValue)
    {
        $this->dirValue = $dirValue;
    }
    public function setStartValue($startVlaue)
    {
        $this->startVlaue = $startVlaue;
    }
    public function setLengthValue($lengthVlaue)
    {
        $this->lengthVlaue = $lengthVlaue;
    }



}
