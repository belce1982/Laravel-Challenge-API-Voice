<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoiceRequest extends FormRequest
{
    /**
     * The URI that users should be redirected to if validation fails.
     *
     * @var string
     */
    protected $redirect = '/';    
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
            'question_id'=>'required|int|exists:questions,id',
            'value'=>'required|boolean',
        ];
    }
}
