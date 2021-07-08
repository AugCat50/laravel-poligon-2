<?php

namespace App\Observers;

use App\Models\BlogPost;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class BlogPostObserver
{
    /**
     * Handle the BlogPost "creating" event.
     * Действия перед сохранением записи
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
        $this->setHtml($blogPost);
        $this->setUser($blogPost);
    }

    /**
     * Handle the BlogPost "updating" event.
     * Действия перед обновлением записи
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function updating(BlogPost $blogPost)
    {
        //Изменилась ли модель. Если поменялось хоть одно поле, вернёт true
        // $test[] = $blogPost->isDirty();

        //Изменилось ли конкретное поле модели
        // $test[] = $blogPost->isDirty('is_published');
        // $test[] = $blogPost->isDirty('user_id');

        //Получить значение уже изменённого атрибута (который будет сохранён)
        // $test[] = $blogPost->getAttribute('is_published');
        // $test[] = $blogPost->is_published;

        //Получить старое значение, находящееся сейчас в базе, до изменения
        // $test[] = $blogPost->getOriginal('is_published');

        // dd($test);

        $this->setPublishedAt($blogPost);
        $this->setSlug($blogPost);
    }

    /**
     * Если дата публикации не установлена и происходит установка флага - опубликовано, то устанавливаем дату публикации на текущую.
     * 
     * @param BlogPost $blogpost
     */
    protected function setPublishedAt(BlogPost $blogPost)
    {
        if (empty($blogPost->published_at) && $blogPost->is_published) {
            $data['published_at'] = Carbon::now();
        }
    }

    /**
     * Если slug пуст, заполяем конвертацией заголовка
     * 
     * @param BlogPost $blogpost
     */
    protected function setSlug(BlogPost $blogPost)
    {
        if (empty($blogPost->slug)) {
            $blogPost->slug = \Str::slug($blogPost->title);
        }
    }

    /**
     * Установка занчения полю content_html относительно поля content_raw 
     * 
     * @param BlogPost $blogpost
     */
    protected function setHtml(BlogPost $blogPost)
    {
        if ($blogPost->isDirty('content_raw')) {
            //TODO: Тут должна быть генерация markdown -> html
            $blogPost->content_html = $blogPost->content_raw;
        }
    }

        /**
     * Если slug пуст, заполяем конвертацией заголовка
     * 
     * @param BlogPost $blogpost
     */
    protected function setUser(BlogPost $blogPost)
    {
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }

    /**
     * Handle the BlogPost "deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "restored" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function restored(BlogPost $blogPost)
    {
        //
    }

    /**
     * Handle the BlogPost "force deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function forceDeleted(BlogPost $blogPost)
    {
        //
    }
}
