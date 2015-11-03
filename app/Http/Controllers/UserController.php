<?php

namespace MyApiRest\Http\Controllers;


/*use MyApiRest\Http\Requests;*/
use MyApiRest\Http\Controllers\Controllers;
use MyApiRest\Http\Requests\LoginForm;
use MyApiRest\Http\Requests\SignupForm;
use MyApiRest\Contracts\Auth\Guard;
use MyApiRest\Contracts\Auth\Registrar;
use MyApiRest\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Hash;
use Illuminate\Http\Request;
use MyApiRest\User;

class UserController extends Controller
{
    //inicia sesion de usuario en la aplicacion
    public function login(LoginForm $request)
    {
        $user = $request->input('email');
        $password = $request->input('password');
       
        // Verificamos los datos
        if (Auth::attempt($request->all())) 
        {   
            if (Auth::user()->enabled == 1) {

                // Si nuestros datos son correctos devolvemos un JSON con el nombre del usuario
                return response()->json([
                    'name'=> User::where('email', '=', $user)->pluck('name')
                ], 200);
            }else{
                // Si la cuenta no esta activada devolvemos un JSON de error y cerramos la session y enviamos de nuevo un correo de verificacion
                Auth::logout();
                $data = array
                (
                    'id'        =>  User::where('email', '=', $user)->pluck('id'),
                    'email'     =>  $user,
                    'token'     =>  User::where('email', '=', $user)->pluck('password'),
                );

                //enviamos el correo de verificacion (para realizar pruebas cambiar en la base
                // de datos en la tabla "users" el campo active y poner un 1)
                $url = "http://".$_SERVER['HTTP_HOST'];
                $headers = "Content-type: text/html";
                $html1 = '<a target="_blank" href="';
                $html2 = '"></a>';
                $message = "Click aqui para confirmar su cuenta".$url."/#/verify_account/".$data['id']."/".$data['token'];
                
                Auth::logout();
                mail($user,'Confirmar su cuenta en YoDecido.net ',$message,$headers);

                return response()->json([
                   'active' => 'Su cuenta no esta activada, se ha enviado un mensaje de verificaion porfavor revise su correo: '.$user.' para activarla'
                ], 200);
                
            }
            

        }else{
            // Si los datos no son los correctos devolvemos un JSON de error
            return response()->json([
              'error' => 'El email o la Contraseña no son correctos'
            ], 200);
        }


    }

    //cierra sesion de usuario en la aplicacion
    public function logOut() {

        Auth::logout();
        return ['success' => 'sesíon finalizada'];

    }

    /**
     * verifica si hay una sessio activa en el momento
     *
     */
    public function session()
    {
        if (Auth::guest()){

            return response()->json([
               'error'=> 'guest'
            ], 200);
            

        }else{

            // Si nuestros datos son correctos devolvemos un JSON con el primer nombre del usuario
            return response()->json([
               'name'=> Auth::user()->name1,'avatar'=> Auth::user()->avatar
            ], 200);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * crearun nuevo usuario
     *
     */
    public function create(SignupForm $request)
    {
        

        // Si nuestros datos pasan la validacion creamos un nuevo objeto con base al modelo User para crear un nuevo usuario en la base de datos
        $user = new User($request->all());
        $user->avatar = 'data:image/png;base64,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';
        $user->password =  Hash::make($request->password);
        $user->save();

       
        return response()->json([
            'success'=>'Usuario creado correctamente'
        ], 200);
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
