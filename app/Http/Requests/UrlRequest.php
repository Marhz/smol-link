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
        if ($this->isMethod('post')) {
            $rules = [
                'url' => ['required', 'regex:' . $this->urlRegex()],
                'label' => ['max:255'],
          ];
        }
        else if ($this->isMethod('put'))
            $rules = [
                'label' => ['max:255'],
                'slug' => [
                    'required',
                    Rule::unique('urls', 'slug')->ignore($this->route('url')->id),
                    Rule::notIn($this->reservedNames())
                ]
            ];
        return $rules;
    }

    protected function urlRegex()
    {
        return '/^(((https?|ftps?):\/\/)?(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)/';
    }

    protected function reservedNames() {
        return [
            'dashboard',
            'login',
            'logout',
            'register'
        ];
    }
}
