<?php

namespace XattaTrone\LaravelBackupUi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaravelBackupUiDownloadRequest extends FormRequest
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
            'disk' => ['required', 'string'],
            'filename' => ['required', 'string'],
        ];
    }
}
