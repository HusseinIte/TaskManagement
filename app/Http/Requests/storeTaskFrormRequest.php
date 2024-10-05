<?php

namespace App\Http\Requests;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class   storeTaskFrormRequest extends FormRequest
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
        return [
            'title'=>'required|string|min:3|max:100',
            'description'=>'required|string|min:3|max:100',
            'priority'=>['required','string','in:' . implode(',', array_column(Priority::cases(), 'value'))],
            'due_date'=>'required|date',
            'status'=>['nullable','in:' . implode(',', array_column(TaskStatus::cases(), 'value'))],
            'assigned_to'=>'nullable|exists:users,id',
            'created_by'=>'nullable|exists:users,id'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'errors' => $validator->errors(),
        ], 422));
    }
}
