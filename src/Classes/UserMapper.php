<?php

namespace Classes;

class UserMapper extends Mapper
{
    public function getUsers() {
        $sql = "SELECT * from users ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        $results = [];
        while($row = $stmt->fetch()) {
            $results[] = new UserEntity($row);
        }
        return $results;
    }

    public function save(UserEntity $ticket) {
        $sql = "insert into users
            (email, name, lastname, address, telephone, dateBirth, dateRegister, user, password, mailchimp, keyConfirm) values
            (:email, :name, :lastname, :address, :telephone, :dateBirth, :dateRegister, :user, :password, :mailchimp, :keyConfirm)";
        $stmt = $this->db->prepare($sql);

        $variables_usuario = $ticket->getParameters(['id','userConfirm', 'dateLastlogin']);

        foreach ($variables_usuario as $key => $valor) {
            $stmt->bindValue(":$key", $valor);
        }

        $result = $stmt->execute();
        if(!$result) {
            throw new Exception("No se ha podido guardar el usuario");
        }
    }
    public function update(UserEntity $user, $updateDateLastLogin = false) {

        if($updateDateLastLogin) {
            $datelastLogin = 'datelastLogin = :datelastLogin,';
        }

        $sql = "update users SET
            email = :email,
            name = :name,
            lastname = :lastname,
            address = :address,
            telephone = :telephone,
            dateRegister = :dateRegister,
            dateBirth = :dateBirth,
            user = :user,
            password = :password,
            mailchimp = :mailchimp,
            keyConfirm = :keyConfirm,
            $datelastLogin
            userConfirm = :userConfirm
            where id=:id";
        $stmt = $this->db->prepare($sql);

        if ($updateDateLastLogin) {
            $variables_usuario = $user->getParameters();
        } else {
            $variables_usuario = $user->getParameters(['dateLastlogin']);
        }

        foreach ($variables_usuario as $key => $valor) {
            echo "$key => $valor<br>";
            $stmt->bindValue(":$key", $valor);
        }

        $result = $stmt->execute();
        if(!$result) {
            throw new Exception("No se ha podido guardar el usuario");
        }
    }


    public function getUserByUsername($user) {
        $sql = "SELECT *
            from users
            where user = :user";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["user" => $user]);
        $result = $stmt->fetch();
        if($result) {
            return new UserEntity($result);
        }
    }
    public function getUserByEmail($email) {
        $sql = "SELECT *
            from users
            where email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(["email" => $email]);
        $result = $stmt->fetch();
        if($result) {
            return new UserEntity($result);
        }
    }
}