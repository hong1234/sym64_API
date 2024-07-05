<?php
namespace App\Dao;

class ReviewDao extends BaseDao {

    public function reviewInsert(iterable $params=[]){
        $sql = "INSERT INTO reviews SET email = :email, name = :name, content = :content, like_status = :like_status, created_on = NOW(), book_id = :book_id";
        return $this->doSQL($sql, $params);
    }

    public function reviewDelete(iterable $params=[]){
        $sql = "DELETE FROM reviews WHERE id = :id";
        return $this->doSQL($sql, $params);
    }

    public function reviewsByBookId(iterable $params=[]) {
        $sql = "SELECT * FROM reviews WHERE book_id = :book_id";
        return $this->doQuery($sql, $params);
    }

    public function getReview(iterable $params=[]) {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        return $this->doQuery($sql, $params);
    }
}