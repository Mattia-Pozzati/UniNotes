<?php


/**
 * L'obbiettivo è definire un query builder da poter usare come piccolo ORM. 
 * Non voglio imparare laravel e non ho voglia di scrivere sql
 */

interface BaseInterface {

    public function where($coloumn, $op, $value);
    public function order_by($coloumn, $order);
    public function limit($number);
    public function select($params);
    public function get();
    public function insert($data);
    public function update($data);
    public function delete();

}








?>