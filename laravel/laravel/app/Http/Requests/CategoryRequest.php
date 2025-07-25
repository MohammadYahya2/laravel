<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Since we're using Supabase, we can't use the unique rule directly
        // We'll need to handle uniqueness in the controller
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
} 