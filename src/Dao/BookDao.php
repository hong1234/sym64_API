<?php
namespace App\Dao;

class BookDao extends BaseDao {

    public function bookInsert(iterable $params=[]){
        $sql = "INSERT INTO books SET title = :title, content  = :content";
        return $this->doSQL($sql, $params);
    }

    public function bookAll() {
        $sql = "SELECT * FROM books";
        return $this->doQuery($sql);  // $result
    }

    public function bookDelete(iterable $values=[]){
        $sql = "DELETE FROM reviews WHERE book_id = :id";
        $this->doSQL($sql, $values);
        $sql = "DELETE FROM books WHERE id = :id";
        return $this->doSQL($sql, $values);
    }
}