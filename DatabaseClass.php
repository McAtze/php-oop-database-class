<?php
class DatabaseClass {	

    private ?PDO $connection = null;

    /**
     * This function is called everytime this class is instantiated
     * @param string $dbhost
     * @param string $dbname
     * @param string $username
     * @param string $password
     * @throws Exception
     */
    public function __construct(
        string $dbhost = 'localhost',
        string $dbname = 'dbName',
        string $username = 'userName',
        string $password = '') {
        try {
            $this->connection = new PDO("mysql:host=$dbhost;dbname=$dbname;", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }			
    }

    /**
     * Insert a row/s in a database table
     * @param string $statement
     * @param array $parameters
     * @return bool|string
     * @throws Exception
     */
    public function insert(string $statement = '', array $parameters = []): bool|string
    {
        try {
            $this->executeStatement($statement, $parameters);

            return $this->connection->lastInsertId();
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }		
    }

    /**
     * Select a row/s in a database table
     * @param string $statement
     * @param array $parameters
     * @return bool|array
     * @throws Exception
     */
    public function select(string $statement = '', array $parameters = []): bool|array
    {
        try {
            $stmt = $this->executeStatement($statement, $parameters);

            return $stmt->fetchAll();
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }		
    }

    /**
     * Update a row/s in a database table
     * @param string $statement
     * @param array $parameters
     * @throws Exception
     */
    public function update(string $statement = '', array $parameters = []): void
    {
        try {
            $this->executeStatement($statement, $parameters);
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }		
    }

    /**
     * Remove a row/s in a database table
     * @param string $statement
     * @param array $parameters
     * @throws Exception
     */
    public function remove(string $statement = '', array $parameters = []): void
    {
        try {
            $this->executeStatement($statement, $parameters);
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }		
    }

    /**
     * Execute statement
     * @param string $statement
     * @param array $parameters
     * @return bool|PDOStatement
     * @throws Exception
     */
    private function executeStatement(string $statement = '', array $parameters = []): bool|PDOStatement
    {
        try {
            $stmt = $this->connection->prepare($statement);
            $stmt->execute($parameters);

            return $stmt;
        }
        catch(Exception $e) {
            throw new Exception($e->getMessage());   
        }		
    }   
}