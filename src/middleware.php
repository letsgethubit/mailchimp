<?php
// Application middleware
use Respect\Validation\Validator as v;
use \DavidePastore\Slim\Validation\Validation as Validation;


//Create the validators
$emailValidator = v::email();
$nameValidator = v::notEmpty()->length(2, 50);
$telephoneValidator = v::optional(v::phone());
$dateValidator = v::optional(v::date());
$usernameValidator = v::alnum()->noWhitespace()->length(1, 10);
$passwordValidator = v::notEmpty()->length(3, 50);
//$politicasValidator = v::notBlank();
$ageValidator = v::numeric()->positive()->between(1, 20);
$validators = array(
    'email' => $emailValidator,
    'name' => $nameValidator,
    'lastname' => $nameValidator,
    'telephone' => $telephoneValidator,
    'date_birth' => $dateValidator,
    'user' => $usernameValidator,
    'password' => $passwordValidator,
    'password2' => $passwordValidator,
//    'politicas' => $politicasValidator,
);
$translator = function($message){

    $messages = [
        'These rules must pass for {{name}}' => 'Queóste regole devono passare per {{name}}',
        '{{name}} must have a length between {{minValue}} and {{maxValue}}' => 'Debe tener entre {{minValue}} y {{maxValue}} carácteres',
        '{{name}} must be a valid date' => 'Debe ser una fecha válida',
//        '{{name}} must not be blank' => 'No debe dejarse sin marcar',
        '{{name}} must contain only letters (a-z)' => 'Debe contener solo letras',
        '{{name}} must be a valid telephone number' => 'Debe ser un número de teléfono válido',
        '{{name}} must contain only letters (a-z) and digits (0-9)' => 'Debe contener únicamente letras y números',
        '{{name}} must be valid email' => 'Debe ser un email válido',
        '{{name}} must not be empty' => 'No puede estar vacío',
    ];
    if(!isset($messages[$message])) {
        return $message;
    }
    return $messages[$message];
};
$middlewareValidator = new Validation($validators, $translator);


$middlewareRestriccionesValidator = function ($request, $response, $next) {

    if (!$request->getAttribute('has_errors')) {

        $data = $request->getParsedBody();

        $UserMaper = new \Classes\UserMapper($this->db);
        $usuario1 = $UserMaper->getUserByUsername($data['user']);
        $usuario2 = $UserMaper->getUserByEmail($data['email']);

        $errores = [];

        if ($data['password'] != $data['password2']) {
            $errores['password2'] = ['Las contraseñas no coinciden'];
        }

        if (!is_null($usuario1)){
            $errores['user'] = ['Ya existe un usuario con este nombre'];
        }

        if (!is_null($usuario2)){
            $errores['email'] = ['Ya existe un usuario con ese email'];
        }

        if (is_null($data['politicas'])) {
            $errores['politicas'] = ['No puedes registrarte sin aceptar las políticas'];
        }

        if(0 < count($errores)) {
            $request = $request->withAttribute('errors', $errores);
            $request = $request->withAttribute('has_errors', true);
        }
    }

    $response = $next($request, $response);
    return $response;
};