<?php

class UsersController extends Controller{

    public $UserModel;
    public $GroupModel;

    public function __construct($data = array()){
        parent::__construct($data);
        $this->UserModel = new User();
        $this->GroupModel = new Group();
    }

    public function register(){
        if( $_POST ){
            $result = $this->UserModel->register($_POST);
            if ($result) {
                $user = $this->UserModel->getById($result);
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Session::setFlash('Вы зарегистрированы');
                Router::redirect('/');
            }else{
                Session::setFlash('Ошибка при регистрации');
                Router::redirect('/users/register');
            }
        }
    }

    public function login(){
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
            $user = $this->UserModel->getByLogin($_POST['login']);
            $hash = md5(Config::get('salt').$_POST['password']);

            if ( $user && $user['is_active'] && $hash == $user['password'] ){

                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }else {
                Session::setFlash('Неверные данные');
            }
            if ( $user['role'] == '2'){
                Router::redirect('/admin/');
            }
            Router::redirect('/');
        }
    }

    public function logout(){
        Session::destroy();
        Router::redirect('/');
    }

    public function admin_index(){
        $this->data['users'] = $this->UserModel->getUsersList();
    }


    public function admin_add(){
        if( $_POST ){
            $result = $this->UserModel->register($_POST);
            if ($result) {
                $this->UserModel->getById($result);
                Session::setFlash('Пользователь добавлен');
                Router::redirect('/admin/users/');
            }else{
                Session::setFlash('Ошибика');
                Router::redirect('/admin/users/');
            }
        }
    }

    public function admin_close(){

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = (int)$params[0];
            $this->data['group'] = $this->UserModel->getGroups($id);
            $this->data['user_id'] = $id;
        }

        if( $_POST ){
            $user_id = $_POST['user_id'];
            $group = $_POST['group'];
            $result = $this->UserModel->closeGroup($user_id,$group);
            if ($result) {
                Session::setFlash('Пользователь удален с группы');
                Router::redirect('/admin/users/');
            }else{
                Session::setFlash('Ошибка');
                Router::redirect('/admin/users/');
            }
        }
    }

    public function admin_enter(){

        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = (int)$params[0];
            $groups = $this->GroupModel->getList();
            $user_groups = $this->UserModel->getGroups($id);

            if($user_groups){
                foreach($groups as $g){
                    foreach($user_groups as $ug){
                        if($g['id'] != $ug['id']){
                            $this->data['groups'][''] = $g;
                        }
                    }
                }
            }else{
                $this->data['groups'] = $groups;
            }
            $this->data['user_id'] = $id;

        }

        if( $_POST ){
            $user_id = $_POST['user_id'];
            $group = $_POST['group'];
            $result = $this->UserModel->enterGroup($user_id,$group);
            if ($result) {
                Session::setFlash('Пользователь добавлен в группу');
                Router::redirect('/admin/users/');
            }else{
                Session::setFlash('Ошибка');
                Router::redirect('/admin/users/');
            }
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->UserModel->delete($this->params[0]);
            if ($result){
                Session::setFlash('User was deleted.');
            }else{
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/users/');
    }


}