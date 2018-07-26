<?php

/**
 * Created by PhpStorm.
 * User: AI System
 * Date: 20-Jul-18
 * Time: 12:44 PM
 */
class Database
{

    public function connect(){
        try{
            $conn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USER,DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            echo 'Connect Fail' . $e->getMessage();
        }
        return $conn;
    }

    public function insert_user($user_id,$display_name,$display_image,$status_message){
        $sql = "insert into users(id,display_name,display_image,status_message) values(:id,:display_name,:display_image,:status_message)";

        $statement = $this->connect()->prepare($sql);
        $statement->execute(array(
            'id' => $user_id,
            'display_name' => $display_name,
            'display_image' => $display_image,
            'status_message' => $status_message
        ));

    }

    public function insert_work($user_id,$name){
        $sql = "insert into works(user_id,name) VALUES(:user_id,:name) ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute(array(
           'user_id' => $user_id,
            'name' => $name
        ));
    }

    public function get_user($id = FALSE){
        if($id === FALSE){
            $stmt = $this->connect()->prepare("SELECT * FROM users");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        $stmt = $this->connect()->prepare("SELECT * FROM users where id=:id");
        $stmt->execute(array(
            'id'=>$id
        ));
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function get_work($user_id = FALSE){
        if($user_id === FALSE){
            $stmt = $this->connect()->prepare("SELECT * FROM works");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        $stmt = $this->connect()->prepare("SELECT * FROM works where user_id=:id");
        $stmt->execute(array(
            'id'=>$user_id
        ));
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
