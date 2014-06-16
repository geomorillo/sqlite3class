sqlite3class
============

Sqlite 3 class

Simple to use sqlite3 php class

to use include it like this <br>

<code> include './sqlite_class.php';</code>


to create a db <br>
<code> $db = new DB("mydb.db"); // you can use txt or db as extension</code>

to create a table use an array here is an example <br>
<code>
$values = array( <br>
    "TABLE" => "jugadores", // table name <br>
    "id" => "INTEGER PRIMARY KEY", <br>
    "oro" => "INTEGER(11)", <br>
    "plata" => "INTEGER(11)", <br>
    "cobre" => "INTEGER(11)" <br>
); <br>

$db->CreateTable($values);
</code>
easily insert data with an array <br>
<code>
$fields = array( <br>
    "id" => 1, <br>
    "oro" => 100, <br>
    "plata" => 0, <br>
    "cobre" => 0 <br>
);<br>

$db->Insert("jugadores", $fields); <br>
</code>
selecting data use an array <br>
<code>
$values = array(<br>
    "ROWS" => "*", //row names<br>
    "TABLE" => "jugadores",<br>
    "WHERE" => array("oro" => 100, "plata" => 0), //use an array for more than 1, here is the sql: WHERE oro = 100 AND plata = 0<br>
    "ORDER" => "id"<br>
);<br>
$rows = $db->Select($values); // here it returns an array with the selected rows<br>

$db->close(); // finally close the database<br>

</code>
