<?php
/**
 * Created by PhpStorm.
 * User: modth
 * Date: 12/11/2018
 * Time: 8:20 PM
 */

trait connect
{
    private $conn;

    /**
     * connect constructor.
     * @param $conn
     */
    public function __construct(\PDO $conn)
    {
        $this->conn = $conn;
    }


}