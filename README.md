sqlite3class
============

Sqlite 3 class

Simple to use sqlite3 php class

to use include it like this <br>

<code> include './sqlite_class.php';</code>


to create a db <br>
<code> $db = new DB("mydb.db"); // you can use txt or db as extension</code>

to create a table use an array here is an example <br>
```
$values = array( 
    "TABLE" => "jugadores", // table name 
    "id" => "INTEGER PRIMARY KEY",
    "oro" => "INTEGER(11)", 
    "plata" => "INTEGER(11)", 
    "cobre" => "INTEGER(11)" 
); 

$db->CreateTable($values);
```
easily insert data with an array <br>
```
$fields = array( <br>
    "id" => 1, <br>
    "oro" => 100, <br>
    "plata" => 0, <br>
    "cobre" => 0 <br>
);<br>

$db->Insert("jugadores", $fields); 
```
selecting data use an array <br>
```
$values = array(
    "ROWS" => "*", //row names
    "TABLE" => "jugadores",
    "WHERE" => array("oro" => 100, "plata" => 0), //use an array for more than 1, here is the sql: WHERE oro = 100 AND plata = 0
    "ORDER" => "id"
);<br>
$rows = $db->Select($values); // here it returns an array with the selected rows<br>

$db->close(); // finally close the database

```
