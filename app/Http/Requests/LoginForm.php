<?php namespace MyApiRest\Http\Requests;

use MyApiRest\Http\Requests\Request;
class LoginForm extends Request {

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
	        "email"    =>    "required|email",
	        "password"    =>    "required|min:6"
	    ];
	}

	public function messages()
    {
        return [            
            'email.required' => 'Por favor ingrese su direccion de correo electronico',
            'email.email' => 'Por favor ingrese una direccion de correo valida',
            'password.required' => 'Por favor ingrese la contraseña',
            'password.min' => 'La contraseña debe tener minimo 6 caracteres alfanuméricos',
        ];
    }

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

}
