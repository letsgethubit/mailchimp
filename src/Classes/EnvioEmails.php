<?php

namespace Classes;


class EnvioEmails
{

    private $usuario;
    private $config;
    /**
     * EnvioEmails constructor.
     */
    public function __construct(\Classes\UserEntity $usuario, $config)
    {
        $this->usuario = $usuario;
        $this->config = $config;
    }

    public function envia()
    {
        $data = [
          'fullName' => $this->usuario->getFullName(),
          'keyConfirm' => $this->usuario->getKeyConfirm(),
          'user' => $this->usuario->getUser()
        ];

        $subject = $this->config['subject'];
        $fromAdress = $this->config['fromEmail'];
        $fromName = $this->config['fromName'];
        $toAdress = $this->usuario->getEmail();
        $toName = $this->usuario->getFullName();
        $templates = $this->config['urlTemplates'];

        $emailVo = (new \Simplon\Email\Vo\EmailVo())
            ->setPathContentTemplates($templates)
            ->setFrom($fromAdress, $fromName)
            ->setTo($toAdress, $toName)
            ->setSubject($subject)
            ->setContentData($data);

        $emailTransportVo = new \Simplon\Email\Vo\EmailTransportVo(
            \Swift_MailTransport::newInstance()
        );

        if (!(new \Simplon\Email\Email($emailTransportVo))->sendEmail($emailVo)) {
            throw new \Exception('No se ha podido enviar el email');
        }
    }
}