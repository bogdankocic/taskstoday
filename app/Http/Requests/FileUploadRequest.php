<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'type' => 'required|string|in:task,project',
            'id' => 'required|integer|exists:projects,id|exists:tasks,id',
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240|mimes:png,jpg,jpeg,pdf,csv,json',
        ];
    }
}
