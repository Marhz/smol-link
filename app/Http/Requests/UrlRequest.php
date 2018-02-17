<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UrlRequest extends FormRequest
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
        $rules = [
            'url' => ['required', 'regex:' . $this->urlRegex()],
            'label' => ['max:255'],
        ];
        if ($this->isMethod('put'))
            $rules['slug'] = [Rule::unique('urls', 'slug')->ignore($this->route('url')->id)];
        return $rules;
    }

    protected function urlRegex()
    {
        return '#^(((https?|ftp)://)?(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';
    }
}
