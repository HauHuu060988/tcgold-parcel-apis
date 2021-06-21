<?php /** @noinspection PhpUndefinedMethodInspection */

namespace App\Http\Requests\v1;

use Illuminate\Validation\Rule;
use Urameshibr\Requests\FormRequest;
use Illuminate\Http\Response as Response;

class ParcelRequest extends FormRequest
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
     * Merge query parameters to the data to validate.
     *
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        $data = parent::all();
        $id = isset($this->route()[2]['id']) ? $this->route()[2]['id'] : null;
        if (in_array(strtolower($this->method()), ['put', 'delete']) || $id) {
            $data['id'] = isset($this->route()[2]['id']) ? $this->route()[2]['id'] : null;
        }

        $this->query('parcelIds') && $data['parcelIds'] = $this->query('parcelIds');

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = strtolower($this->method());
        $sPricingModel = array_keys(config('common.pricing_models'));
        $sPricingModel = implode(',', $sPricingModel);

        $rules = [
            'name' => ['required', 'unique:parcels'],
            'weight' => ['required', 'numeric'],
            'volume' => ['required', 'numeric'],
            'value' => ['required', 'numeric'],
            'model' => ['required', 'integer', 'in:' . $sPricingModel],
        ];

        switch ($method) {
            case 'post':
                break;
            case 'put':
                $id = isset($this->route()[2]['id']) ? $this->route()[2]['id'] : null;
                $rules['id'] = ['required', 'integer'];
                $rules['name'] = [
                    Rule::unique('parcels', 'name')
                        ->where(function ($query) use ($id) {
                            return $query->whereRaw('id <> ?', [intval($id)]);
                        }),
                ];
                break;
            case 'delete':
                $rules = ['id' => ['required', 'integer']];
                break;
            default:
                $rules = [];
                if (isset($this->route()[2]['id'])) {
                    $rules['id'] = ['integer'];
                } else {
                    $rules['parcelIds'] = [
                        'required',
                        function ($attribute, $value, $fail) {
                            $idList = explode(',', $value);
                            if (!empty($value)) {
                                if (count($idList) > 20) {
                                    $fail('Parcels are not allowed to exceed 20 parcel id.');
                                }
                            }
                            foreach ($idList as $id) {
                                if (!ctype_digit($id)) {
                                    $fail('The parcel id must be integer.');
                                    break;
                                }
                            }
                        }
                    ];
                }
                break;
        }
        return $rules;
    }
}
