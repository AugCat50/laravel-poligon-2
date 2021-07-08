<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $title[] = $this->title;
        // $title[] = $this->slug;
        // $title[] = $this->category_id;
        // $title[] = $this->content_raw;

        // dd(__METHOD__, $title);
        return [
            'title'       => 'required|min:5|max:200|unique:blog_posts',
            'slug'        => 'max:200|unique:blog_posts',
            'content_raw' => 'required|string|min:5|max:10000',
            'category_id'   => 'required|integer|exists:blog_categories,id'
        ];
    }

    /**
     * Get the error messages for the defined volidation rules
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'title.required'  =>'Введите заголовок статьи',
            'content_raw.min' => 'Минимальная длина статьи [:min] символов'
        ];
    }

    /**
     * Get custom attributes for validator errors
     * 
     * @return array
     */
    public function attributes()
    {
        return [
            'title'  =>'Заголовок'
        ];
    }
}
