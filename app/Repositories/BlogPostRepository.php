<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use App\Repositories\CoreRepository;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class BlogPostRepository.
 */
class BlogPostRepository extends CoreRepository
{
    private static $instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new BlogPostRepository();
        }
        self::$instance->getModelInstance();
        return self::$instance;
    }


    /**
     * @return string
     *  Return the model
     */
    protected function getModelInstance()
    {
        self::$instance->model = app(Model::class);
    }

    /**
     * Получить категории для вывода с пагинацией
     * 
     *  @param int|null $perPage
     * 
     * @return \Illuminate\Contacts\Pagination\LengthAwarePagginator
     */
    public function getAllWithPaginate(int|null $perPage = null)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id'
        ];

        $result = $this->startConditions()
                    ->select($columns)
                    ->orderBy('id', 'DESC')
                    // ->with(['category', 'user'])
                    ->with([
                        'category' => function ($query)
                        {
                            $query->select(['id', 'title']);
                        },
                        'user:id,name'
                    ])
                    ->paginate($perPage);

        return $result;
    }

    /**
     * Получить модель для редактирования в админке
     * 
     * @param int $id
     * 
     * @return Model
     */
    // public function getEdit(int $id)
    // {
    //     return $this->startConditions()->find($id);
    // }
}
