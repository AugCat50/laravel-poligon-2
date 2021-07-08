<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Observers\BlogPostObserver;
use App\Jobs\BlogPostAfterCreateJob;
use App\Repositories\BlogPostRepository;
use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Repositories\BlogCategoryRepository;

/**
 * Управление статьями блога
 * 
 * @package App\Http\Controllers\Blog\Admin
 */
class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();

        $this->blogPostRepository     = BlogPostRepository::getInstance();
        $this->blogCategoryRepository = BlogCategoryRepository::getInstance();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository
        ->getAllWithPaginate(25);

        return view('blog.admin.posts.index', compact('paginator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item         = new BlogCategory();
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogPostCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();

        //Создать объект и сохранить в БД
        $item = (new BlogPost())->create($data);

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            return redirect()->route('blog.admin.posts.edit', [$item->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd(__METHOD__, $id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) abort(404);

        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return view('blog.admin.posts.edit', compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BlogPostUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return back()
                ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();

        // Вынесено в обсервер BlogPostObserver
        //Если slug пустой, сгенерировать его из title
        // if (empty($data['slug'])) {
        //     $data['slug'] = \Str::slug($data['title']);
        // }

        //Установить время публикации, в случае если оно пустое
        // if (empty($item->published_at) && $data['is_published']) {
        //     $data['published_at'] = Carbon::now();
        // }

        $result = $item->update($data);

        if ($result) {
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
            ->withErrors(['msg' => "ошибка сохраннения"])
            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Soft Delete
        //$result получает количество удалённых записай
        //Так же сработает обсервер, если имеется
        $result = BlogPost::destroy($id);
        
        //Final Delete
        // $result = BlogPost::find($id)->forceDelete();

        if ($result) {
            //Выполнить job  с отсрочкой 20 секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => 'Запись ID: '.$id.' удалена']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }
}
