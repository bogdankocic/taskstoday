<?php

namespace App\Http\Requests;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TaskCreateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'team_id' => 'required|exists:teams,id',
        ];
    }

    /**
     * Configure the validator instance.
     */
    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $projectId = $this->input('project_id');
            $teamId = $this->input('team_id');

            // Check if the team belongs to the project
            $teamBelongsToProject = Team::where('id', $teamId)
                ->where('project_id', $projectId)
                ->exists();

            if (!$teamBelongsToProject) {
                $validator->errors()->add('team_id', 'The team does not belong to the selected project.');
            }
        });
    }
}
