<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'description'       => 'nullable|string',
            'location'          => 'nullable|string',
            'hide_like_view'    => 'boolean',
            'allow_commenting'  => 'boolean',
            'type'              => 'required|in:post,reel',
            'visibility'        => 'required|in:public,private',
            'image'             => 'nullable|string',
        ];
    }
}
