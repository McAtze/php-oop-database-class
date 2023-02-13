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

`$instance = new PDO( dsn , username , password)`

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
````
Notice we have a `:id` in our SQL statement. We are telling PDO that we have an input that needs to be escaped. And it will look for the correct key in our parameters
PDO does the escaping automatically and you don't need to worry about injections.

Returns Boolean.

### Fetching Data from the Database
`PDOStatement::fetchAll();`
```php
$pdoStatement = $instance->prepare('Select * from tableName where id = :id');
$pdoStatement->execute(['id'=>1]);
$data = $pdoStatement->fetchAll();
//var_dump($data);
```

Returns object or array.

## Creating the Class
Preparing our Class name, variables and functions.
```php
class DatabaseClass{	
	
        private $connection = null;

        // this function is called everytime this class is instantiated		
        public function __construct(){
            
        }
		
        // Insert a row/s in a Database Table
        public function Insert( ){
            
        }

        // Select a row/s in a Database Table
        public function Select( ){
            
        }
		
        // Update a row/s in a Database Table
        public function Update( ){
            
        }		
		
        // Remove a row/s in a Database Table
        public function Remove( ){
            
        }		
        
        // execute statement
        private function executeStatement( ){
			
        }
		
    }
```
Now that we have a simple design for our Database class. Lets fill the functions with some codes.

Establish the MySQL connection in the costructor.
```php
// this function is called everytime this class is instantiated
public function __construct( $dbhost = "localhost", $dbname = "myDataBaseName", $username = "root", $password    = ""){
  try {
    $this->connection = new PDO("mysql:host={$dbhost};dbname={$dbname};", $username, $password);
    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	 } catch(Exception $e){
    throw new Exception($e->getMessage());   
    }			
}
```
The constructor will have 4 parameters:
- $dbhost - The database host.
- $dbname - The database name.
- $username The database User.
- $password - The database password for the User.
