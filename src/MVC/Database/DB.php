<?php

namespace MVC\Database;

/**
 * Description of class DB
 * @author Ramon Serrano
 * @package MVC\database
 */
abstract class DB {

    /**
     * Name of DataBase
     * @var string $db_name
     */
    protected $db_name;

    /**
     * Driver of connection
     * @var string $driver
     */
    protected $driver;

    /**
     * Server of connection
     * @var string $server
     */
    protected $host;

    /**
     * Port of connection
     * @var int $port
     */
    protected $port;

    /**
     * Username to Database connection
     * @var string $user
     */
    protected $user;

    /**
     * Password of Username to Database connection
     * @var string $pass
     */
    protected $pass;

    /**
     * Insert Last Id
     * @var int $queryLastId
     */
    public $queryLastId = null;
    
    /**
     * Number of rows affected by MySQL query.
     * @var int $affected_rows
     */
    public $affected_rows = 0;

    /**
     * Name of table of consults
     * @var string $table
     */
    protected $table;

    /**
     * Set value for table
     * @param string $table
     * return void
     */
    public function setTable($table) {
        $this->table = $table;
    }

}