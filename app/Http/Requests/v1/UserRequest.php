<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Requests\v1;

use Urameshibr\Requests\FormRequest;
use Illuminate\Http\Response as Response;

class UserRequest extends FormRequest
{
    /**
     * Determine if the request passes the authorization check.
     *
     * @return boolean
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors)
    {
        $msgError = [];
        foreach ($errors as $key => $value) {
            $msgError = array_merge($msgError, $value);
        }
        return getResponse($msgError, null, false, null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required','max:255'],
        ];
    }
}
