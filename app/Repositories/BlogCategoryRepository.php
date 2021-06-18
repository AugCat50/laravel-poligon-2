<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BlogcategoryRepository.
 */
class BlogCategoryRepository extends CoreRepository
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new BlogCategoryRepository();
        }
        return self::$instance;
    }

    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        //return YourModel::class;
    }

    
    public function getEdit(int $id)
    {
        //return YourModel::class;
    }

    public function getForComboBox()
    {
        //return YourModel::class;
    }
}
