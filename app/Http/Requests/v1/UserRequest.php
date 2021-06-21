<?php

namespace App\Http\Requests\v1;

class UserRequest extends BaseRequest
{
    /**
     * [authorize description]
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * [response description]
     * @param array $errors [description]
     * @return json         [description]
     */
    public function response(array $errors)
    {
        return $this->__returnResponseErrors($errors);
    }

    /**
     * set rule for table groups.
     * @return array
     */
    public function rules()
    {
        if (strtoupper($this->method()) == 'POST') {
            $rules = [];
            foreach ($this->files as $key => $value) {
                if (isset($key) && $key == 'image') {
                    $rules[$key] = 'mimes:jpeg,bmp,png,jpg';
                }
            }
            return $rules;
        } else {
            return [];
        }
    }

    /**
     * [messages description]
     * @return array [description]
     */
    public function messages()
    {
        return [
            'phone.regex' => trans('validation.userPhoneRegex'),
            'phone.max' => trans('validation.userPhoneRegex'),
            'image.mimes' => trans('validation.userImageMimes'),
            'product_group_id.required' => trans('validation.userProductGroupIdRequired'),
            'product_group_id.integer' => trans('validation.userProductGroupIdInteger'),
        ];
    }

    // /**
    //  *  Filters to be applied to the input.
    //  *
    //  * @return array
    //  */
    // public function filters()
    // {
    //     return [
    //         'email' => 'trim|lowercase',
    //         'name' => 'trim|capitalize|escape'
    //     ];
    // }
}
