<?php
namespace Core\ORM;

/**
 * L'obbiettivo è definire un query builder da poter usare come piccolo ORM. 
 * Non voglio imparare laravel e non ho voglia di scrivere sql
 */

interface BaseInterface {

    public function where($coloumn, $op, $value, $and = true) : BaseInterface;
    public function order_by($coloumn, $order) : BaseInterface;
    public function limit($number) : BaseInterface;
    public function select($params) : BaseInterface;
    public function get() : array;
    public function insert(array $data) : string | bool;
    public function update(array $data) : bool;
    public function delete() : bool;
    public function first() : array | null;
    public function count() : int;
    public function leftJoin(string $table, string $first, string $operator, string $second) : BaseInterface | null;
    public function rightJoin(string $table, string $first, string $operator, string $second) : BaseInterface | null;
    public function join(string $table, string $first, string $operator, string $second, string $type = "INNER") : BaseInterface | null;
    public function paginate(int $perPage = 12, int $page = 1) : array;
}








?>