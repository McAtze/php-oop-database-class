# PHP OOP Database Class Example

A simple PHP Database Class using PDO as the extension.

In this post I will be showing you how to create a reusable, readable Object-Oriented Programming (OOP) Database connection using PHP PDO to connect to a MySQL Database.

## Quick Explanation

By Default PHP comes with 3 MySQL API
- MySQL
- MySQLi
- PDO
You can read Choosing between MySQL, MySQLi and PDO for a comparison for each.

In this example we will be using the PDO API.

Here's a brief explanation of some PDO codes that we will be using.

### Creating a PDO instance

`$instance = new PDO(dsn, username, password)`

- dsn [Required] - The Data Source Name that contains information required to connect to the database
- username [Required] - MySQL Username
- password [Required] - MySQL Password

Returns a PDO instance or Throws an error.

### Preparing and Executing a MySQL statement
`PDO::prepare(statement);`
- statement [Required] - An SQL statement to execute
```php
$instance->prepare('Select * from tableName');
```
`PDOStatement::execute(parameters);`
- parameters [Optional] - An array of key => value
```php
$pdoStatement = $instance->prepare('Select * from tableName where id = :id');
$pdoStatement->execute(['id'=>1]);
```
Notice we have a `:id` in our SQL statement. We are telling PDO that we have an input that needs to be escaped. And it will look for the correct key in our parameters
PDO does the escaping automatically and you don't need to worry about injections.

Returns Boolean.

### Fetching Data from the Database
`PDOStatement::fetchAll();`

```php
$pdoStatement = $instance->prepare('Select * from tableName where id = :id');
$pdoStatement->execute(['id'=>1]);
$data = $pdoStatement->fetchAll();
var_dump($data);
```

Returns object or array.

## Creating the Class
Preparing our Class name, variables and functions.
```php
class DatabaseClass {	
  private $connection = null;

  // this function is called everytime this class is instantiated		
  public function __construct() {}
		
  // Insert a row/s in a Database Table
  public function insert() {}

  // Select a row/s in a Database Table
  public function select() {}
		
  // Update a row/s in a Database Table
  public function update() {}		
		
  // Remove a row/s in a Database Table
  public function remove() {}		
        
  // execute statement
  private function executeStatement() {}
}
```
Now that we have a simple design for our Database class. Lets fill the functions with some codes.

Establish the MySQL connection in the costructor.
```php
// this function is called everytime this class is instantiated
public function __construct($dbhost = 'localhost',$dbname = 'dbName', $username = 'userName', $password = '') {
  try {
    $this->connection = new PDO("mysql:host={$dbhost};dbname={$dbname};", $username, $password);
    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch(Exception $e) {
    throw new Exception($e->getMessage());   
  }			
}
```
The constructor will have 4 parameters:
- `$dbhost` - The database host.
- `$dbname` - The database name.
- `$username` - The database User.
- `$password` - The database password for the User.

### A Function that will execute all statements
```php
/** Execute statement */
private function executeStatement($statement = '', $parameters = []) {
  try {
    $stmt = $this->connection->prepare($statement);
    $stmt->execute($parameters);
    return $stmt;
  } catch(Exception $e) {
      throw new Exception($e->getMessage());   
  }		
}
```
We will be passing our SQL Statements to this function (Insert, Select, Update and Remove).
Returns a PDOStatement object or throws an exception if it get's an error.

### Insert Function
```php
/** Insert a row/s in a Database Table */
public function insert($statement = '', $parameters = []) {
  try {
    $this->executeStatement($statement, $parameters);
    return $this->connection->lastInsertId();
  } catch(Exception $e){
    throw new Exception($e->getMessage());   
  }		
}
```
Insert will add a row and will return an integer of the last ID inserted or throws an exception if it get's an error.

### Select Function
```php
/** Select a row/s in a database table */
public function select($statement = '', $parameters = []) {
  try {
    $stmt = $this->executeStatement($statement, $parameters);
    return $stmt->fetchAll();
  } catch(Exception $e) {
    throw new Exception($e->getMessage());   
  }		
}
```
Select will return all row/s or throws an exception if it get's an error.

### Update Function
```php
/** Update a row/s in a database table */
public function update($statement = '', $parameters = []) {
  try {
    $this->executeStatement($statement, $parameters);
  } catch(Exception $e){
    throw new Exception($e->getMessage());   
  }		
}
```
Update will update a row/s or throws an exception if it get's an error.

### Remove Function
```php
/** Remove a row/s in a database table */
public function remove($statement = '', $parameters = []) {
  try {
    $this->executeStatement($statement, $parameters);
  } catch(Exception $e){
    throw new Exception($e->getMessage());   
  }		
}
```
Remove will remove a row/s or throws an exception if it get's an error.

## Using the database class
### Create/Instantiate the dtabase class.
```php
$db = new Database(
  'host',
  'dbName',
  'userName',
  'password'
);
```
### Insert example
```php
$id = $db->insert("Insert into `TableName`( `column1` , `column2`) values ( :column1 , :column2 )", ['column1' => 'column1 Value', 'column2' => 'column2 Value']);
```	
### Select example
```php
$db->select("Select * from TableName");
```
### Update example
```php
$db->update("Update TableName set `column1` = :column1 where id = :id", ['id' => 1, 'column1' => 'a new column1 value']);
```
### Remove example
```php
$db->remove("Delete from TableName where id = :id", ['id' => 1]);
```

## Tips:
Minimize connections to your server.

Take this as an example:
```php
for($x = 1; $x <= 1000; $x++) {
  $db = new Database(
    'host',
    'dbName',
    'userName',
    'password'
  );
  $data = $db->select("Select * from TableName where id = :id",["id"=>$x]);
  // do something with $data
}
```
The above code will create 1000 connections and this could lead to your server to slowing down.

A better way to do this is to create the DatabaseClass object before the looping:
```php
$db = new Database(
  'host',
  'dbName',
  'userName',
  'password'
);
for($x = 1; $x <= 1000; $x++) {
  $data = $db->select("Select * from TableName where id = :id",["id"=>$x]);
  // do something with $data
}
```
The above code will create 1 connection and will use it inside the loop.

## Credits
Original by https://devjunky.com/PHP-OOP-Database-Class-Example/
