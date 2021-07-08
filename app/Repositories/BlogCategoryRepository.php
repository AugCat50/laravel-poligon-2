<?php

namespace App\Repositories;

use App\Repositories\CoreRepository;
//use Your Model
use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;

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

    /**
     *  Получить список категорий для вывода в выпадаюзем списке
     * 
     * @return Collection
     */
    public function getForComboBox()
    {
        // return $this->startConditions()->all();

        $columns = implode(', ', [
            'id',
            'parent_id',
            'CONCAT (id, ". ", title) AS id_title'
        ]);

        // $result[] = $this->startConditions()->all();
        // $result[] = $this
        //     ->startConditions()
        //     ->select('blog_categories.*',
        //         \DB::raw('CONCAT (id, ". ", title) AS id_title'))
        //     ->toBase()
        //     ->get();

        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            ->toBase()
            ->get();

        // dd($result);
        return $result;
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
        $columns = ['id', 'title', 'parent_id'];

        $result = $this->startConditions()
                    ->select($columns)
                    ->with(['parentCategory:id,title'])
                    ->paginate($perPage);

        return $result;
    }
}
