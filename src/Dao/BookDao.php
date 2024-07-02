<?php
namespace App\Dao;

class BookDao extends BaseDao {

    public function bookInsert(iterable $params=[]){
        $sql = "INSERT INTO books SET title = :title, content = :content, created_on = NOW()";
        return $this->doSQL($sql, $params);
    }

    public function bookDelete(iterable $params=[]){
        $sql = "DELETE FROM reviews WHERE book_id = :id";
        $this->doSQL($sql, $params);
        $sql = "DELETE FROM books WHERE id = :id";
        return $this->doSQL($sql, $params);
    }

    public function bookAll() {
        $sql = "SELECT * FROM books";
        return $this->doQuery($sql);
    }
}