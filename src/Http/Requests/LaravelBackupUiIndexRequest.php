<?php

namespace XattaTrone\LaravelBackupUi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaravelBackupUiIndexRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'disk' => ['nullable', 'string'],
            'sort' => ['required', 'string', 'in:asc,desc'],
            'per_page' => ['required', 'integer'],
            'page' => ['required', 'integer'],
        ];
    }

    /**
     * Prepares the data for validation.
     *
     * @return void
     */
    public function prepareForValidation()
    {
        $this->merge([
            'sort' => $this->sort ? $this->sort : 'asc',
            'per_page' => $this->per_page ? $this->per_page : 10,
            'page' => $this->page ? $this->page : 1,
        ]);
    }
}
