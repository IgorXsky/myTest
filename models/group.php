<?php

class Group extends Model{

    public function getList(){
        $sql = "select * from groups";

        return $this->db->query($sql);
    }

    public function getByAlias($alias){
        $alias = $this->db->escape($alias);
        $sql = "select * from groups where alias = '{$alias}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getById($id){
        $id = (int)$id;
        $sql = "select * from groups where id = '{$id}' limit 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $id = null){
        if ( !isset($data['alias']) || !isset($data['title']) || !isset($data['content']) ){
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);

        if ( !$id ){ // Add new record
            $sql = "
                insert into groups
                   set alias = '{$alias}',
                       title = '{$title}',
                       content = '{$content}'
            ";
        } else { // Update existing record
            $sql = "
                update groups
                   set alias = '{$alias}',
                       title = '{$title}',
                       content = '{$content}'
                   where id = {$id}
            ";
        }
        return $this->db->query($sql);
    }

    public function delete($id){
        $id = (int)$id;
        $res = "SELECT * FROM user_group WHERE id_group = {$id} ";
        $is_empty = $this->db->query($res);
        if (!$is_empty) {
            $sql = "DELETE FROM groups WHERE id = {$id}";
            return $this->db->query($sql);
        } else {
            return false;
        }
    }

}