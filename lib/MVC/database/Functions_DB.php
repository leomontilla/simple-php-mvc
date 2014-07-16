<?php

namespace MVC\database;

use \MVC\database\DB;

/**
 * Description of Functions_DB
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC\database
 */
class Functions_DB extends DB {

    /**
     * The db handle.
     *
     * @var \stdClass $_handle
     */
    protected $_handle;

    /**
     * Constructor of the Functions_DB
     *
     * @param string $path_config_file
     */
    public function __construct($path_config_file) {
        if (file_exists($path_config_file)) {
            $database = include $path_config_file;
            $this->host = $database['host'];
            $this->port = $database['port'];
            $this->db_name = $database['db_name'];
            $this->display_sql = $database['display_sql'];
            $this->user = $database['user'];
            $this->pass = $database['password'];
            $this->socket = $database['socket'];
            $this->_handle = @new \mysqli("$this->host", "$this->user", "$this->pass", "$this->db_name");
            if ($this->_handle->connect_error) {
                $this->error("Error: " . $this->_handle->connect_error);
            }
            $this->_handle->set_charset("utf8");
            
        } else {
            $this->error("No existe el archivo: $path_config_file.");
        }
    }

    /**
     * Destruct of the class
     * @return void
     */
    public function __destruct() {
        $this->_handle = NULL;
    }

    /**
     * Select all from the table
     * @param string $where
     * @param array $join
     * @return array
     */
    public function all($where = null, $join = null) {
        return $this->select("SELECT * FROM $this->table $where", $join);
    }

    /**
     * Delete SQL
     * @param string $where
     * @return boolean
     */
    protected function _delete($where) {
        if ($this->query("DELETE FROM $this->table $where")) {
            $this->affected_rows = $this->_handle->affected_rows;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Views of errors
     * @param string $error
     */
    protected function error($error) {
        print '<pre>';
        \MVC\errors\Exception::run($error);
        print '</pre>';
    }

    /**
     * Select row from the table where id
     * @param string $id
     * @return array|bool
     */
    public function find($id = null) {
        if ($id != NULL) {
            $result = $this->select("SELECT * FROM $this->table WHERE id = $id");
            if (isset($result[0])) {
                return $result[0];
            }
        } else {
            return false;
        }
    }

    /**
     * Sets the last insert id
     * @return void
     */
    protected function lastInsertId() {
        $result = $this->select("SELECT LAST_INSERT_ID(id) as id FROM $this->table");
        $this->queryLastId = $result[0]['id'];
    }

    /**
     * Insert SQL
     * @param array $values
     * @param array $fields
     * @return bool
     */
    protected function insert($values = array(), $fields = array()) {
        $sql = "INSERT INTO $this->table";
        if (!empty($fields)) {
            $sql .= "(";
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[($i + 1)])) {
                    $sql .= "$fields[$i],";
                } else {
                    $sql .= "$fields[$i])";
                }
            }
        }
        if (!empty($values)) {
            $sql .= " VALUES(";
            for ($i = 0; $i < count($values); $i++) {
                if ($values[$i] == null) {
                    $sql .= "null,";
                } elseif (!empty($values[($i + 1)])) {
                    if ($values[$i] == 'NOW()' || $values[$i] == 'now()') {
                        $sql .= "$values[$i],";
                    } else {
                        $sql .= "'$values[$i]',";
                    }
                } else {
                    if ($values[$i] == 'NOW()' || $values[$i] == 'now()') {
                        $sql .= "$values[$i])";
                    } else {
                        $sql .= "'$values[$i]')";
                    }
                }
            }
            if ($this->query($sql)) {
                $this->lastInsertId();
                $this->affected_rows = $this->_handle->affected_rows;
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Generates the JOIN SQL
     *
     * @param mixed $table             Table(s) that JOIN with the SELECT Table
     * @param mixed $field_first       First(s) field. Can be array or string
     * @param mixed $field_second      Second(s) field. Can be array or string
     * @param mixed $operator          Operator for the JOIN. Can be string or array
     * @return string                  SQL JOIN with the parameters.
     */
    public function join($table, $field_first, $field_second, $operator) {
        $join = "";
        #If the parameters are array
        if ((is_array($table) && is_array($field_first)) && (is_array($field_second) && is_array($operator))) {
            #If the number of keys of the parameters are iquals
            if ((count($table) == count($field_first) && count($table) == count($field_second)) && count($table) == count($operator)) {
                for ($i = 0; $i < count($table); $i++) {
                    $join .= " INNER JOIN $table[$i] ON $field_first[$i] $operator[$i] $field_second[$i] ";
                }
            } else {
                $this->error("Los parámetros no tienen el mismo número de elementos.");
            }
        } elseif (!is_array($table) || !is_array($field_first) || !is_array($field_second) || !is_array($operator)) {
            $join = " INNER JOIN $table ON $field_first $operator $field_second ";
        } else {
            $this->error("Los parámetros no son del mismo tipo");
        }
        return $join;
    }

    /**
     * Function to do queries
     * 
     * @param string $sql
     * @return handle
     */
    protected function query($sql = null) {
        if (strtoupper($this->display_sql) === "HTML") {
            print "<pre>$sql</pre>\n";
        }
        if (strtoupper($this->display_sql) === "PHP") {
            print "\n$sql\n";
        }
        
        $result = $this->_handle->query($sql);
        if ($this->_handle->error) {
            $this->error("Error al ejecutar SQL: {$this->_handle->error}");
        } else {
            return $result;
        }
    }

    /**
     * Max field_name of table
     * 
     * @param string $field_name
     * @return array
     */
    public function max($field_name) {
        $sql = "SELECT MAX($field_name) FROM $this->table";
        $result = $this->select($sql);
        if (isset($result[0])) {
            return $result[0]["MAX($field_name)"];
        }
    }

    /**
     * Min field_name of table
     * 
     * @param string $field_name
     * @return array
     */
    public function min($field_name) {
        $sql = "SELECT MIN($field_name) FROM $this->table";
        $result = $this->select($sql);
        if (isset($result[0])) {
            return $result[0]["MIN($field_name)"];
        }
    }

    /**
     * Function select
     * 
     * @param string $sql
     * @param array $join
     * @param string $where
     * @return array
     */
    public function select($sql = null, $join = null, $where = null) {
        if (is_array($join)) {
            if (count($join) == 4) {
                $keys = array_keys($join);
                $sql .= $this->join($join[$keys[0]], $join[$keys[1]], $join[$keys[2]], $join[$keys[3]]);
            } else {
                $this->error("Valores incorrectos del join. Array['table']Array['field_f']Array['field_s']Array['operator']. Array[0]Array[1]Array[2]Array[3].");
            }
        }
        if ($where) {
            $sql .= $where;
        }
        if (!empty($sql)) {
            $rows = array();
            $result = $this->query($sql);
            try {
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[] = $row;
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            return $rows;
        }
    }

    /**
     * Select specific fields
     *
     * @param array $fields
     * @param string $where
     * @param array $join
     * @return array
     */
    public function select_fields($fields, $join = null, $where = null) {
        $sql = "SELECT ";
        for ($i = 0; $i < count($fields); $i++) {
            if (!empty($fields[($i + 1)])) {
                $sql .= "$fields[$i], ";
            } else {
                $sql .= "$fields[$i] ";
            }
        }
        $sql .= "FROM $this->table";
        return $this->select($sql, $join, $where);
    }

    /**
     * Update SQL
     * @param array $values
     * @param array $fields
     * @param string $where Description
     * @return bool
     */
    protected function _update($values = array(), $fields = array(), $where = null) {
        $sql = "UPDATE $this->table SET ";
        if ((!empty($fields) && !empty($values) ) && count($fields) == count($values)) {
            for ($i = 0; $i < count($fields); $i++) {
                if (!empty($fields[($i + 1)])) {
                    $sql .= "$fields[$i] = '$values[$i]', ";
                } else {
                    $sql .= "$fields[$i] = '$values[$i]' ";
                }
            }
        } elseif (!empty($values) && empty($fields)) {
            foreach ($values as $key => $value) {
                if (end($values) == $value) {
                    $sql .= "$key = '$value' ";
                } else {
                    $sql .= "$key = '$value', ";
                }
            }
        } else {
            $this->error("Parámetros incorrectos para la consulta: Valores: " . json_encode($values) . ". Campos: " . json_encode($fields));
        }

        $sql .= $where;
        if ($this->query($sql)) {
            $this->affected_rows = $this->_handle->affected_rows;
            return true;
        } else {
            return false;
        }
    }

}
