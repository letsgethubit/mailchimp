<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/', function (Request $request, Response $response) {
    $messages = $this->flash->getMessages();

    $response = $this->view->render(
        $response,
        "index.phtml",
        ["mensajes" => $messages]
    );
    return $response;
})->setName('index');


$app->get('/usuario/new', function (Request $request, Response $response) {
    $response = $this->view->render(
        $response,
        "new.phtml"
    );
    return $response;
});

$app->get('/politicas', function (Request $request, Response $response) {
    $response = $this->view->render(
        $response,
        "politicas.phtml"
    );
    return $response;
});

$app->post('/usuario/new', function (Request $request, Response $response) {

    if ($request->getAttribute('has_errors')) {
        $errors = $request->getAttribute('errors');

        $respuesta = [
            'error' => 1,
            'data' => $errors
        ];
    } else {
        $hoy = date('Y/m/d');

        $data = $request->getParsedBody();
        $hashPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $infokeyConfirm = $hoy . round(microtime(true) * 1000) . $data['name'] . $data['email'];
        $hashKeyConfirm = hash('ripemd160',$infokeyConfirm);

        $usuario = new \Classes\UserEntity($data);
        $usuario->setPassword($hashPassword);
        $usuario->setDateRegister($hoy);
        $usuario->setKeyConfirm($hashKeyConfirm);


        $UserMapper = new \Classes\UserMapper($this->db);

        $error = false;
        $data = '/';

        try {
            $UserMapper->save($usuario);

            $mensaje = [
                '¡Felicidades '.$usuario->getFullName().' !',
                'Se ha guardado correctamente tu usuario.',
                'Confirma tu cuenta clicando en el enlace del email que te acabamos de enviar.',
                'Una vez confirmado recibirás los boletines de MailChimp y tendrás acceso a tu cuenta.',
                $usuario->getUser(),
                $hashKeyConfirm,
                '¡Gracias por tu registro!'
            ];

            foreach ($mensaje as $lineas) {
                $this->flash->addMessage('info',$lineas);
            }

            $EnvioEmails = new Classes\EnvioEmails($usuario,$this->settings['email']);
            $EnvioEmails->envia();

        } catch(Exception $e) {
            $data = [
                'servidor' => [$e->getMessage()]
            ];

            $error = true;
        }

        $respuesta = [
            'error' => $error,
            'data' => $data
        ];

    }
    $response = $response->withJson($respuesta);
    return $response;

})->add($middlewareRestriccionesValidator)->add($middlewareValidator);


$app->get('/usuario/{user}/confirmar/{keyConfirm}', function (Request $request, Response $response, $args) {
    $user = $args['user'];
    $keyConfirm = $args['keyConfirm'];

    $UserMaper = new \Classes\UserMapper($this->db);
    $usuario = $UserMaper->getUserByUsername($user);

    if ($usuario->getUserConfirm()) {
        $mensaje = [
            'Su usuario ya se encuentra dado de alta en la lista.',
            'Ya puede loguearse en el sistema.'
        ];
        foreach ($mensaje as $lineas) {
            $this->flash->addMessage('info',$lineas);
        }
    } else if ($usuario->getKeyConfirm() != $keyConfirm) {
        $mensaje = [
            'La clave introducida no es válida',
            'Vuelve a visitar el enlace'
        ];
        foreach ($mensaje as $lineas) {
            $this->flash->addMessage('error',$lineas);
        }
    } else if ($usuario->getKeyConfirm() == $keyConfirm){

        $usuario->setKeyConfirm(null);
        $usuario->setUserConfirm(1);
        $UserMaper->update($usuario);

        $mensajeMailChimp = 'Le recordamos que NO se ha dado de alta a la lista de MailChimp';
        if ($usuario->getMailchimp()) {
            $mailChimpClient = new \Classes\MailChimpClient($this->settings['mailchimp']['apiKey']);
            //$res = $mailChimpClient->getListInfo('5286751517');
            //$res = $mailChimpClient->getListMembers($this->settings['mailchimp']['idList']);
            $mailChimpClient->subscribe($this->settings['mailchimp']['idList'], $usuario);
            $mailChimpClient->updateMember($this->settings['mailchimp']['idList'], $usuario);
            $mensajeMailChimp = 'Se ha suscrito a la lista de MailChimp.';
        }

        $mensaje = [
            '¡Felicidades '.$usuario->getFullName().'!',
            'Se ha dado de alta en la lista.',
            $mensajeMailChimp,
            'Ya puede loguearse en el sistema.'
        ];

        foreach ($mensaje as $lineas) {
            $this->flash->addMessage('info',$lineas);
        }
    }

    $url = $this->get('router')->pathFor('index');
    $response = $response->withRedirect($url);
    return $response;
});
