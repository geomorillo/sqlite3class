<?php

class DB extends SQLite3 {

    var $lang;
    var $newline = "<br>";

    public function __construct($dbase = "") {
        $this->lang = ""; //change  to en for language support
        if ($dbase != "") {
            $this->crearDb($dbase);
        } else {
            //base de datos no definida se crea una por defecto
            $this->crearDb("default.db");
        }
    }

    private function crearDb($dbase) {
        if ($this->extensionValida($dbase) == true) {
            $this->open($dbase);
        }
        if (!file_exists($dbase)) {
            @unlink(basename($dbase));
            die($this->alert(10));
        } else {
            echo "Database ok $this->newline";
        }
    }

    function extensionValida($db) {
        list($db1, $db2) = array('db', 'txt');
        if (is_string($db)) {
            list($name, $ext) = explode(".", $db);
            if (($ext != $db1) && ($ext != $db2)) {
                die($this->alert(11));
            } else {
                return true;
            }
        }
    }

    function CreateTable($values) {
        foreach ($values as $Rows => $Type) {
            $arrfields[] = $Rows;
            $dataType[] = $Type;
            $arrValues[] = "" . $Rows . " " . $Type . "";
        }
        $tabla = $values["TABLE"];
        $arr_rows = implode(", ", $arrfields);
        $this->fields = substr($arr_rows, 10, strlen($arr_rows));
        $strFields = implode(", ", $arrValues);
        $Fields = substr($strFields, strlen($tabla) + 7, strlen($strFields));
        if ($this->CheckTable($tabla) != true) {
            $this->exec("CREATE TABLE " . $tabla . " (" . $Fields . ")");
            echo $this->mensaje(0, $tabla); // tabla creada con exito
        } else {
            print $this->alert(12, $tabla);
        }
    }

    /**
     * Inserta datos usando un array
     * @param string $table
     * @param array $fields
     * @return type
     */
    function Insert($table, $fields) {
        if (is_array($fields)) {
            foreach ($fields as $rows => $values) {
                $arrRows[] = "" . $rows . "";
                $arrValues[] = "'" . $values . "'";
            }

            $strRows = implode(", ", $arrRows);
            $strValues = implode(", ", $arrValues);
        }
        $query = "INSERT INTO " . $table . "($strRows) VALUES ($strValues);";
        $insert = $this->exec($query);

        if (!$insert) {
            print $this->alert(13);
        }
        return $insert;
    }

    /**
     * Select a table using an array as argument
     * @param array $args values = array(    
     *                                  "ROWS"     => "nombre, email, password",
     *                                  "TABLE"    => "usuarios",
      /*                                  "WHERE"   => array("col1"=>"value","col2"=>value), //if array >1 se concatena con AND
      /*                                  "ORDER"    => "email ASC" );
     * @return array 
     */
    function Select($args) {
        if (is_array($args)) {
            $rows = '';
            $where = '';
            $order = '';
            if ($args["ROWS"] != '') {
                $rows = $args["ROWS"];
            }
            if (isset($args["ORDER"]) && $args["ORDER"] != '') {
                $order = "ORDER BY " . $args["ORDER"];    /////  tambien se puede usar "ORDER"=>"name ASC"
            }
            if ($args["TABLE"] != '') {
                $table = $args["TABLE"];
            }

            if (isset($args["WHERE"]) && $args["WHERE"] != '') {

                if (is_array($args["WHERE"])) {
                    $where_arr = [];

                    $i = 0;
                    foreach ($args["WHERE"] as $key => $value) {
                        $where_arr[$i] = "$key  ='$value'";
                        $i++;
                    }
                    $where = "WHERE " . implode(" AND ", $where_arr);// OR NOT SUPPORTED YET
                }
            }

            $query = "SELECT $rows FROM $table $where $order";
            $ret = $this->query($query);
            return $ret->fetchArray(SQLITE3_ASSOC);
        } else {
            return false; //NO EJECUTADA
        }
    }

    /**
     * Update a record with an array
     * @param array $args example: array {
     *                                      "TABLE" => "table-name",
     *                                      "ROWS"  => array(col-name => value, col-name =>value ),
     *                                      "WHERE" => array(col-name => value, col-name =>value )
     *                                   }
     */
    function Update($args) {
        if (is_array($args)) {
            $where = "";
            if ($args["TABLE"] != '') {
                $table = $args["TABLE"];
            }
            if (is_array($args["ROWS"])) {
                $rows_arr = [];

                $i = 0;
                foreach ($args["ROWS"] as $key => $value) {
                    $rows_arr[$i] = "$key  ='$value'";
                    $i++;
                }
                $rows = "SET " . implode(", ", $rows_arr);
            }
            if (isset($args["WHERE"]) && $args["WHERE"] != '') {

                if (is_array($args["WHERE"])) {
                    $where_arr = [];

                    $i = 0;
                    foreach ($args["WHERE"] as $key => $value) {
                        $where_arr[$i] = "$key  ='$value'";
                        $i++;
                    }
                    $where = "WHERE " . implode(" AND ", $where_arr);
                }
            }
            $query = "UPDATE $table $rows $where";
            $ret = $this->query($query);
            return $ret->fetchArray(SQLITE3_ASSOC);
        } else {
            return FALSE;
        }
        //TODAVIA NO IMPLEMENTADO
        // WHERE ES OPCIONAL
        //UPDATE $table SET column-name = "value", column-name="value" WHERE column-name = "value"
    }

    /**
     * Deletes a record with an array 
     * @param array $args example: array(
     *                                   "TABLE" => "tablename",
     *                                   "WHERE" => array("rowname"=>value) //only 1 element allowed
     *                             );
     *                                  
     *                                          
     */
    function Delete($args) {
        //WHERE ES OPCIONAL, SI NO SE DEFINE SE BORRAN TODOS LOS DATOS DE LA TABLA
        //DELETE FROM table_name WHERE [condition];
        if (is_array($args)) {
            $where = "";
            if ($args["TABLE"] != '') {
                $table = $args["TABLE"];
            }
            if (isset($args["WHERE"]) && $args["WHERE"] != '') {

                if (is_array($args["WHERE"])) {
                    $where_arr = [];
                    $i = 0;
                    foreach ($args["WHERE"] as $key => $value) {
                        $where_arr[$i] = "$key  ='$value'";
                        $i++;
                    }
                    $where = "WHERE " . $where_arr[0]; //only 1 where allowed
                }
            }
             $query = "DELETE FROM $table $where";
            $this->query($query);
        }else{
            return FALSE;
        }
    }

    function Count($result) {
        // TODAVIA NO IMPLEMENTADO
    }
    /**
     * Drops a table from the current database
     * @param string $tableName
     */
    function DropTable($tableName){
        //DROP TABLE database_name.table_name;
        $query = "DROP TABLE $tableName";
        $this->exec($query);   
    }

    /*
     * 
     * Chequea que la tabla exista en la bd
     * */

    function CheckTable($table) {
        $result = $this->query("SELECT name FROM sqlite_master WHERE type='table' AND name='" . $table . "'");
        $rows = $result->fetchArray();
        if ($rows != false) {
            return true;
        }
    }

    function alert($num, $parametro = "") {

        switch ($this->lang) {
            case 'en':
                $alert[10] = "Error when trying to create database";
                $alert[11] = "Only extensions *.db and *.txt are valid";
                $alert[12] = "Table already exists change table name";
                $alert[13] = "Error on inserting";
                break;
            default:
                $alert[10] = "Error al intentar crear la base de datos";
                $alert[11] = "Solo las extensiones *.db y *.txt son validas";
                $alert[12] = "Error la tabla $parametro ya existe";
                $alert[13] = "Error al insertar";
        }
        return $alert[$num] . $this->newline;
    }

    function mensaje($num, $parametro) {
        switch ($this->lang) {
            case "en":
                $mensaje[0] = "Table $parametro successfully created";
            default:
                $mensaje[0] = "Tabla $parametro creada con exito";
        }

        return $mensaje[$num] . $this->newline;
    }

    /**
     * Muestra los nombres de las tablas que existen en la base de datos
     */
    function showTables() {
        $query = "SELECT name FROM sqlite_master WHERE type='table'";
        $ret = $this->query($query);
        return $ret->fetchArray(SQLITE3_ASSOC);
    }

}

// fecha a classe
?>
