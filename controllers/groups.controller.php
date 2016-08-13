<?php

class GroupsController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Group();
    }

    public function index(){
        $this->data['groups'] = $this->model->getList();
    }

    public function view(){
        $params = App::getRouter()->getParams();

        if ( isset($params[0]) ){
            $alias = strtolower($params[0]);
            $this->data['group'] = $this->model->getByAlias($alias);
        }
    }

    public function admin_index(){
        $this->data['groups'] = $this->model->getList();
    }

    public function admin_add(){
        if ( $_POST ){
            $result = $this->model->save($_POST);
            if ( $result ){
                Session::setFlash('Group was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/groups/');
        }
    }

    public function admin_edit(){

        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Group was update.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/groups/');
        }

        if ( isset($this->params[0]) ){
            $this->data['group'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong group id.');
            Router::redirect('/admin/groups/');
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Группа удалена');
            } else {
                Session::setFlash('В группе есть активные пользователи');
            }
        }
        Router::redirect('/admin/groups/');
    }

}