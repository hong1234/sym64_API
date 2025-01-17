<?php
namespace App\Dao;

class AccountDao extends BaseDao {

    public function accountInsert(iterable $params=[]){
        $sql = "INSERT INTO user_account SET username = :username, password  = :password";
        return $this->doSQL($sql, $params);
    }

    public function accountDelete(iterable $params=[]){
        $sql = "DELETE FROM user_account WHERE id = :id";
        return $this->doSQL($sql, $params);
    }

    public function accountAll() {
        $sql = "SELECT * FROM user_account";
        return $this->doQuery($sql);
    }

    public function accountFilter(iterable $params) {
        $sql = "SELECT * FROM user_account WHERE username = :username";
        return $this->doQuery($sql, $params);
    }

    public function getAccount(iterable $params=[]) {
        $sql = "SELECT * FROM user_account WHERE id = :id";
        return $this->doQuery($sql, $params);
    }
}