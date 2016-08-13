<?php

class User extends Model {

    public function getById($id){
        $id = (int)$id;
        $sql = "select * from users where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }

    public function getByLogin($login){
        $login = $this->db->escape($login);
        $sql = "select * from users where login = '{$login}' limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }

    public function getUsersList(){
        $sql = "select * from users";
        $result = $this->db->query($sql);

        return $result;
    }


    public function register($data){

        if( !isset($data['login']) || !isset($data['email']) || !isset($data['password'])){
            return false;
        }
        $login = $this->db->escape(strtolower($data['login']));
        $email = $this->db->escape(strtolower($data['email']));

        if( $this->getByLogin($login)){
            return false; // login alredy exist
        }

        $password = $data['password'];
        $hash = md5(Config::get('salt').$password);

        $sql = "INSERT INTO users
                SET
                    login = '{$login}',
                    email = '{$email}',
                    role = '1',
                    password = '{$hash}',
                    is_active = '1'
                ";

        return $this->db->query($sql);

    }


    public function getGroups($id){

        $sql = "SELECT * FROM groups AS g
                JOIN user_group AS ug ON g.id = ug.id_group
                WHERE ug.id_user = {$id} ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
        return false;
    }


    public function closeGroup($user_id, $group){

        $sql = "DELETE FROM user_group WHERE id_user = {$user_id} AND id_group = {$group}";
        return $this->db->query($sql);

    }


    public function enterGroup($user_id, $group){

        $sql = "INSERT INTO `user_group`
                SET id_user = {$user_id},
                    id_group = {$group}
	            ";
        return $this->db->query($sql);

    }


    public function delete($id){
        $id = (int)$id;
        $sql = "delete from users where id = {$id}";
        return $this->db->query($sql);

    }
}