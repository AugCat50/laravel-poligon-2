<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Model;
//use Your Model

/**
 * Class CoreRepository.
 * Репозиторий работы с сущностью. Может выдавать наборы данных, не может создавать и изменять сущности.
 */
abstract class CoreRepository
{
    /**
     * @var Model
     */
    protected $model;

    // public function __construct()
    // {
    //     // $this->model = app($this->setModel());
    // }

    /**
     * @return string
     *  Return the model
     */
    abstract protected function getModelInstance();

    /**
     * @return Model\Illiminate\Foundation\Application\mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }

    /**
     * Получить модель для редактирования в админке
     * 
     * @param int $id
     * 
     * @return Model
     */
    public function getEdit(int $id)
    {
        return $this->startConditions()->find($id);
    }
}
