<?php

namespace Classes;

class UserEntity
{
    protected $id;
    protected $email;
    protected $name;
    protected $lastname;
    protected $address;
    protected $telephone;
    protected $dateBirth;
    protected $dateRegister;
    protected $dateLastlogin;
    protected $user;
    protected $password;
    protected $mailchimp;
    protected $keyConfirm;
    protected $userConfirm;


    /**
     * Accept an array of data matching properties of this class
     * and create the class
     *
     * @param array $data The data to use to create
     */
    public function __construct(array $data) {

        $parametros = $this->getParameters();
        $variables = array_keys($parametros);

        foreach ($data as $key => $value) {
            if(in_array($key,$variables)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * @param mixed $dateBirth
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getMailchimp()
    {
        return $this->mailchimp;
    }

    /**
     * @param mixed $mailchimp
     */
    public function setMailchimp($mailchimp)
    {
        $this->mailchimp = $mailchimp;
    }

    /**
     * @return mixed
     */
    public function getKeyConfirm()
    {
        return $this->keyConfirm;
    }

    /**
     * @param mixed $keyConfirm
     */
    public function setKeyConfirm($keyConfirm)
    {
        $this->keyConfirm = $keyConfirm;
    }

    /**
     * @return mixed
     */
    public function getUserConfirm()
    {
        return $this->userConfirm;
    }

    /**
     * @param mixed $userConfirm
     */
    public function setUserConfirm($userConfirm)
    {
        $this->userConfirm = $userConfirm;
    }

    /**
     * @return mixed
     */
    public function getDateRegister()
    {
        return $this->dateRegister;
    }

    /**
     * @param mixed $dateRegister
     */
    public function setDateRegister($dateRegister)
    {
        $this->dateRegister = $dateRegister;
    }

    /**
     * @return mixed
     */
    public function getDateLastlogin()
    {
        return $this->dateLastlogin;
    }

    /**
     * @param mixed $dateLastlogin
     */
    public function setDateLastlogin($dateLastlogin)
    {
        $this->dateLastlogin = $dateLastlogin;
    }

    public function getFullName() {
        return $this->name . ' ' . $this->lastname;
    }

    public function getParameters($remove = []) {
        $res = get_object_vars($this);

        foreach ($remove as $eliminar) {
            unset($res[$eliminar]);
        }

        return$res;
    }
}