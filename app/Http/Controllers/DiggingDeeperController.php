<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DiggingDeeperController extends Controller
{
    /**
     * Базовая информация
     * @url https://laravel.com/docs/8.x/collections
     * 
     * Справочная информация
     * @url https://laravel.com/docs/8.x/Illuminate/Support/Collection.html
     * 
     * Вариант коллекции для моделей Eloquent
     * @url https://laravel.com/docs/8.x/Illuminate/Database/Eloquent/Collection.html
     * 
     * Билдер запростов - то, с чем можно перепутать коллекции
     * @url https://laravel.com/docs/8.x/queries
     */
    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

        // dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());

        // dd(
        //     get_class($eloquentCollection),
        //     get_class($collection),
        //     $collection
        // );

        $result['first'] = $collection->first();
        $result['last']  = $collection->last();

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');
        //использовать в качестве ключа в массиве поле id
        //->values() - взять только значения, id массива будут стандарные

        // dd($result);

        $result['where']['count']      = $result['where']['data']->count();
        $result['where']['isEmpty']    = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();
        // dd($result);

        // первая запись с полем подпадающим под условие
        $result['where_first'] = $collection->firstWhere('created_at', '>', '2021-04-03 07:06:20');
        // dd($result);

        //Базовая переменная не изменится. ПРосто вернётся изменённая версия.
        $result['map']['all'] = $collection->map(function (array $item) {
            $newItem            = new \stdClass();
            $newItem->item_id   = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists    = is_null($item['deleted_at']);

            return $newItem;
        });
        // dd($result);

        $result['map']['not_exists'] = $result['map']['all']->where('exists', '=', false)->values();
        // dd($result);

        //Базовая коллекция изменится (трансформируется)
        $collection->transform(function (array $item) {
            $newItem            = new \stdClass();
            $newItem->item_id   = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists    = is_null($item['deleted_at']);
            $newItem->createdAt = Carbon::parse($item['created_at']);

            return $newItem;
        });
        // dd($collection);

        $newItem     = new \stdClass();
        $newItem->id = 9999;
        
        $newItem2     = new \stdClass();
        $newItem2->id = 8888;
        // dd($newItem, $newItem2);

        //prepend - добавить элемент а начало коллекции
        $newItemFirst = $collection->prepend($newItem)->first();

        //push - добавить элемент в конец коллекции
        $newItemLast  = $collection->push($newItem2)->last();

        //pull - забрать первый элемент
        $pulledItem   = $collection->pull(1);
        // dd(compact('collection', 'newItemFirst', 'newItemLast', 'pulledItem'));

        // $filtered = $collection->filter(function ($item) {
        //     $byDay = $item->created_at->isFriday();
        //     $byDate = $item->created_at->day == 11;

        //     // $result = 
        //     $result = $byDay && $byDate;

        //     return $result;
        // });

        // dd(compact('filtered'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
