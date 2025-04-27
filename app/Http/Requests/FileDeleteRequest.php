<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileDeleteRequest extends FormRequest
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
            'id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($this->input('type') === 'project' && !\DB::table('project_file')->where('id', $value)->exists()) {
                        $fail('The selected id is invalid for the project type.');
                    } elseif ($this->input('type') === 'task' && !\DB::table('task_file')->where('id', $value)->exists()) {
                        $fail('The selected id is invalid for the task type.');
                    }
                },
            ],
        ];
    }
}
