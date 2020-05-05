<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // TODO: add custom validation using Closure for header params
        return [
            'file' => 'required|mimes:csv,txt',
            'save' => 'required|in:true,false',
        ];
    }
}
