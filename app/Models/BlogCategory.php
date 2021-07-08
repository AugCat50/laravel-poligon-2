<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BlogCategory
 * 
 * @package App\Models
 * 
 * @property-read BlogCategory $parentCategory
 * @property-read string       $parentTitle
 */
class BlogCategory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
                            'title',
                            'slug',
                            'parent_id',
                            'description'
                        ];
    /**
     * ID Корневой категории
     */
    const ID = 1;
    
    /**
     * Получить родительскую категорию
     * 
     * @return BlogCategory
     */
    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    /**
     * Пример акссесора
     * 
     * @url https://laravel.com/docs/8.x/eloquent-mutators
     * 
     * @return string
     */
    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title 
            ?? ($this->isRoot() ? 'Корневая категория' : '???');
        return $title;
    }

    /**
     * Является ли категория корневой
     * 
     * @return bool
     */
    private function isRoot()
    {
        return $this->id === BlogCategory::ID;
    }

    /**
     * Пример акссесора
     * 
     * @param string $valueFromDB
     * 
     * @return bool|mixed|null|string|string[]
     */
    // public function getTitleAttribute($valueFromObject)
    // {
    //     return mb_strtoupper($valueFromObject);
    // }

    /**
     * Пример мутатора
     * 
     * @return string $incomingValue
     */
    // public function setTitleAttribute($incomingValue)
    // {
    //     $this->attributes['title'] = mb_strtolower($incomingValue);
    // }
}
