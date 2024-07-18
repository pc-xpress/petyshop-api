<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePetRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'slug'        => 'required|unique:pets,slug,' . $this->pet->id,
            'species' => 'required|string|max:255',
            'breed' => 'nullable|string|max:255',
            'age' => 'nullable|integer',
            'biography' => 'nullable|string',
            'profile_picture' => 'nullable|string|max:255',

        ];
    }

    protected function prepareForValidation()
    {
        $slug = $this->pet->slug;
        if ($this->get('name') !== $this->pet->name) {
            $slug = str($this->get('name') . ' ' . uniqid())->slug();
        }
        $this->merge([
            'slug' => $slug
        ]);
    }
}
