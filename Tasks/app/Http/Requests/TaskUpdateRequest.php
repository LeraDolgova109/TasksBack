<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:1',
            'description' => 'string',
            'deadline' => 'required|string',
            'category_id' => 'exists:categories,id',
            'progress' => 'required|regex:/^\d+(\.\d+)?$/',
            'status' => 'required|string|in:Created,InProcess,Finished',
            'is_important' => 'boolean'
        ];
    }
}
