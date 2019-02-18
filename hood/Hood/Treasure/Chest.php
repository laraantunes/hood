<?php

namespace Hood\Treasure;

use Hood\Config\Config;
use Hood\Treasure\LockPick\InsertBuilder;
use Hood\Treasure\LockPick\QueryBuilder;
use Hood\Treasure\LockPick\UpdateBuilder;

class Chest
{
    /**
     * @var \PDO
     */
    protected $con;

    /**
     * @var bool
     */
    protected $manualCommit = false;

    /**
     * Array with all the errors handled by instance
     * @var array
     */
    public $errors = [];

    /**
     * @var self
     */
    protected static $instance;

    /**
     * An Array with all the other connections instances loaded. If there's another chest opened, this
     * attribute will store the chests opened before the current. Each key is the connection name plus an
     * increment started by zero
     * @var array
     */
    protected static $instanceList = [];

    /**
     * @var string
     */
    protected static $currentConnection = 'default';

    /**
     * Chest constructor.
     */
    public function __construct() {
        $this->start();
    }

    /**
     * Gets the singleton instance of Chest
     * @return Chest
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Reset the singleton instance of Chest
     */
    public static function resetInstance()
    {
        self::$instance = null;
    }



    /**
     * Gets / Sets the current connection name
     * @param string|null $connectionName
     * @return string
     */
    public static function con($connectionName = null): string
    {
        if (!empty($connectionName)) {
            self::$currentConnection = $connectionName;
        }
        return self::$currentConnection;
    }

    /**
     * Starts the connection
     */
    public function start() {
        // gets the configuration
        $databaseConfig = Config::get(Config::DATABASE, self::con());

        // Builds the dsn based on the driver configured
        $dsn = '';
        // If it's mysql or didn't expected, loads mysql
        if (in_array($databaseConfig['driver'], ['mysql', 'pgsql']) || empty($databaseConfig['driver'])) {
            $driver = $databaseConfig['driver'] ?? 'mysql';
            // Handles host:port
            $hosttmp = $databaseConfig['host'];
            $hosttmp = explode('//', $hosttmp);
            $hosttmp = implode($hosttmp);
            $hosttmp = explode(':',$hosttmp);
            $hostCount = count($hosttmp);
            if($hostCount > 2){
                $host = $hosttmp[$hostCount - 2];
                $port = $hosttmp[$hostCount - 1];
            }else{
                $host = $hosttmp[0];
                $port = $hosttmp[1] ?? $databaseConfig['port'] ?? null;
            }
            $dsn = "{$driver}:dbname={$databaseConfig['name']};host={$host}".(!empty($port) ? ";port={$port}" : '');
        // If it's sql server
        } elseif ($databaseConfig['driver'] == 'mssql') {
            $dsn = "dblib:host={$databaseConfig['host']};dbname={$databaseConfig['name']}";
        // If it's oracle
        } elseif ($databaseConfig['driver'] == 'oracle') {
            $dsn = "oci:dbname={$databaseConfig['tns']}";
        // If it's sqlite
        } elseif ($databaseConfig['driver'] == 'sqlite') {
            $dsn = "sqlite:{$databaseConfig['path']}";
        //If it's odbc
        } elseif ($databaseConfig['driver'] == 'odbc') {
            $dsn = $databaseConfig['path'];
        // If it's custom, loads the string
        } elseif ($databaseConfig['driver'] == 'custom') {
            $dsn = $databaseConfig['custom'] ?? null;
        }

        // Starts the connection
        $this->con = new \PDO(
            $dsn,
            $databaseConfig['user'] ?? null,
            $databaseConfig['password'] ?? null,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );

        // Starts the transaction
        $this->con->beginTransaction();
    }

    /**
     * Opens the Database Chest, moves the current instance to the instance list and returns a new instance
     * @param string|null $connectionName
     * @return Chest
     */
    public static function open(string $connectionName = null)
    {
        // Gets the current connection and instance
        $currentConnectionName = self::con();
        $currentInstance = self::getInstance();

        // Checks if already exists on the instanceList
        $count = 0;
        while(array_key_exists($currentConnectionName . '_' . $count, self::$instanceList)) {
            $count++;
        }

        // Sets on instanceList
        self::$instanceList[$currentConnectionName . '_' . $count] = $currentInstance;

        // Resets the current instance
        self::resetInstance();

        // Sets the connection name
        self::con($connectionName);

        // Returns a new instance of the Chest
        return self::getInstance();
    }

    /**
     * Returns the current instance to the previous instance or closes the passed connection and return the current
     * instance
     * @param string $connectionIdentifier The identifier of the instance to close or null, to close current instance
     * @return self
     */
    public static function close(string $connectionIdentifier = '')
    {
        if (empty($connectionIdentifier)) {
            $previousConnectionName = array_keys(self::$instanceList)[count(self::$instanceList)-1];
            $previousInstance = self::$instanceList[$previousConnectionName];
            self::con(explode('_', $previousConnectionName)[0]);
            self::$instance = $previousInstance;
            unset(self::$instanceList[$previousConnectionName]);
            return self::$instance;
        }
        unset(self::$instanceList[$connectionIdentifier]);
        return self::$instance;
    }

    /**
     * Starts a new transaction
     */
    public function beginTransaction(){
        if(!$this->con->inTransaction()){
            $this->con->beginTransaction();
        }
    }

    /**
     * Sets the manualCommit for current transaction
     * @param bool $manualCommit
     */
    public function manualCommit(bool $manualCommit = true){
        $this->manualCommit = $manualCommit;
    }

    /**
     * Commits only if manualCommitActive is not true
     */
    protected function autoCommit(){
        if(!$this->manualCommit){
            $this->commit();
        }
    }

    /**
     * Commits the current transaction
     */
    public function commit() {
        $this->con->commit();
    }

    /**
     * Rollbacks the current transaction
     */
    public function rollback() {
        $this->con->rollBack();
    }

    /**
     * Gets the class name based on an instance
     * @param $instance
     * @return string
     * @throws \ReflectionException
     */
    protected static function _getClass($instance): string
    {
        $ref = new \ReflectionClass($instance);
        return $ref->getName();
    }

    /**
     * Gets data from a table
     * @param string $table The table to gets the data
     * @param array $columns The columns to get, if empty, return all columns
     * @param mixed $id The id to search. If empty, returns all data from the table
     * @param string|null $searchField The field to search, if different from 'id'
     * @return mixed
     */
    public static function simpleGet(string $table, array $columns = [], $id = null, string $searchField = null)
    {
        $object = new class() extends Model {};
        $object::$table = $table;
        return self::get($object, $columns, $id, $searchField);
    }

    /**
     * Gets data and build an object or a list of objects
     * @param Model $instance The instance to build
     * @param array $columns The columns to get, if empty, gets the columns from the object's fields static attribute
     * @param null $id The objects id to search. If empty, returns all data from the table
     * @param string|null $searchField The primaryKey's from the object, if different from 'id'. If empty, uses the
     * object's primaryKey static attribute
     * @return mixed
     */
    public static function get($instance, array $columns = [], $id = null, string $searchField = null)
    {
        try {
            $table = $instance::getTable();
            if (count($columns) == 0) {
                $columns = array_values($instance::getFields());
                $attributes = $instance::getFields();
            } else {
                $attributes = [];
                foreach ($columns as $column) {
                    $attributes[$column] = $column;
                }
            }
            $qb = QueryBuilder::factory()->table($table);
            if (count($columns) == 0 || $columns[0] == '*') {
                $qb->field("*");
            } else {
                $qb->fields($columns);
            }
            if (!empty($id)) {
                $keyField = $searchField ?? $instance::getPrimaryKey();
                $qb->where("{$keyField} = ?");
            }

            $stmt = self::getInstance()->con->prepare($qb);
            $success = $stmt->execute($id ? [$id] : null);

            if (!$success) {
                return false;
            }

            $dbList = $stmt->fetchAll(\PDO::FETCH_NAMED);

            $list = [];

            foreach ($dbList as $item) {
                $class = self::_getClass($instance);
                $obj = new $class();
                $obj->beforeLoad();
                foreach ($item as $attr => $value) {
                    $attr = array_search($attr, $attributes);
                    $obj->$attr = $value;
                }
                $obj->afterLoad();
                $list[] = $obj;
            }

            return $list;
        } catch (\Throwable $e) {
            self::getInstance()->errors[] = $e;
            return false;
        }
    }

    /**
     * Updates a table registry
     * @param string $table The table to update
     * @param array $values The values to change
     * @param array $columns The columns to update
     * @param mixed $id The key value
     * @param string|null $searchField The search field key
     * @return bool
     */
    public static function simpleUpdate(
        string $table,
        array $values,
        array $columns,
        $id,
        string $searchField = null)
    : bool {
        $object = new class() extends Model {};
        $object::$table = $table;
        foreach ($values as $attr => $value) {
            $object->$attr = $value;
        }
        return self::update($object, $columns, $id, $searchField);
    }

    /**
     * Updates the instance object
     * @param Model $instance The instance to update
     * @param array $columns
     * @param mixed $id
     * @param string|null $searchField
     * @return bool
     */
    public static function update($instance, array $columns = [], $id = null, string $searchField = null): bool
    {
        try {
            $instance->beforeSave();
            $instance->beforeUpdate();

            $table = $instance::getTable();
            if (count($columns) == 0) {
                $columns = array_values($instance::getFields());
                $attributes = array_keys($instance::getFields());
            } else {
                $attributes = $columns;
            }
            $ub = UpdateBuilder::factory()->table($table);
            $values = [];
            foreach ($columns as $key => $column) {
                if (is_array($column)) {
                    $column = $column['column'];
                }
                $attribute = $attributes[$key];
                $values[] = $instance->$attribute;
                $ub->value($column, "?", UpdateBuilder::TYPE_BIND);
            }

            $keyField = $searchField ?? $instance::getPrimaryKey();
            $ub->where("{$keyField} = ?");

            $stmt = self::getInstance()->con->prepare($ub);
            $values[] = $id;
            $success = $stmt->execute($values);

            $instance->afterSave();
            $instance->afterUpdate();

            if (!$success) {
                $instance->afterSaveFail();
                $instance->afterUpdateFail();
                return false;
            }
            self::getInstance()->autoCommit();

            $instance->afterSaveSuccess();
            $instance->afterUpdateSuccess();

            return true;

        } catch (\Throwable $e) {
            self::getInstance()->errors[] = $e;
            self::getInstance()->rollback();

            $instance->afterSaveFail();
            $instance->afterUpdateFail();

            return false;
        }
    }

    /**
     * Inserts a registry into the table
     * @param string $table
     * @param array $values
     * @return bool
     */
    public static function simpleInsert(string $table, array $values): bool {
        $object = new class() extends Model {};
        $object::$table = $table;
        foreach ($values as $attr => $value) {
            $object->$attr = $value;
        }
        return self::insert($object, array_keys($values));
    }

    /**
     * Inserts into database the instance
     * @param Model $instance
     * @param array $columns
     * @return bool
     */
    public static function insert($instance, array $columns = []): bool
    {
        try {
            $instance->beforeSave();
            $instance->beforeInsert();

            $table = $instance::getTable();
            if (count($columns) == 0) {
                $columns = array_values($instance::getFields());
                $attributes = array_keys($instance::getFields());
            } else {
                $attributes = $columns;
            }
            $ib = InsertBuilder::factory()->table($table);
            $values = [];
            foreach ($columns as $key => $column) {
                if (is_array($column)) {
                    $column = $column['column'];
                }
                $attribute = $attributes[$key];
                $values[] = $instance->$attribute;
                $ib->value($column, "?", InsertBuilder::TYPE_BIND);
            }
            $stmt = self::getInstance()->con->prepare($ib);
            $success = $stmt->execute($values);

            $instance->afterSave();
            $instance->afterInsert();

            if (!$success) {
                $instance->afterSaveFail();
                $instance->afterInsertFail();
                return false;
            }

            self::getInstance()->autoCommit();

            $instance->afterSaveSuccess();
            $instance->afterInsertSuccess();

            return true;

        } catch (\Throwable $e) {
            self::getInstance()->errors[] = $e;
            self::getInstance()->rollback();

            $instance->afterSaveFail();
            $instance->afterInsertFail();

            return false;
        }
    }

    /**
     * Deletes a registry based on a table
     * @param string $table
     * @param $id
     * @param string|null $searchField
     * @return bool
     */
    public static function simpleDelete(string $table, $id, string $searchField = null): bool
    {
        $object = new class() extends Model {};
        $object::$table = $table;
        return self::delete($object, $id, $searchField);
    }

    /**
     * Deletes a registry based on a instance
     * @param Model $instance
     * @param $id
     * @param string|null $searchField
     * @return bool
     */
    public static function delete($instance, $id, string $searchField = null): bool
    {
        try {
            $instance->beforeDelete();

            $table = $instance::getTable();
            $keyField = $searchField ?? $instance::getPrimaryKey();
            $sql = "delete from {$table} where {$keyField} = ?";
            $stmt = self::getInstance()->con->prepare($sql);
            $deleted = $stmt->execute($id ? [$id] : null);

            $instance->afterDelete();

            if ($deleted) {
                self::getInstance()->autoCommit();

                $instance->afterDeleteSuccess();
                return true;
            }

            $instance->afterDeleteFail();
            return false;

        } catch (\Throwable $e) {
            self::getInstance()->errors[] = $e;
            $instance->afterDeleteFail();
            return false;
        }
    }

    /**
     * Count registry on passed table
     * @param string $table
     * @param mixed $id
     * @param string|null $searchField
     * @return int
     */
    public static function count(string $table, $id = null, string $searchField = null): int
    {
        try {
            $qb = QueryBuilder::factory()->table($table)->field('count(1) as c');
            if (!empty($id)) {
                $keyField = $searchField ?? Model::getPrimaryKey();
                $qb->where("{$keyField} = ?");
            }
            $stmt = self::getInstance()->con->prepare($qb);
            if (!$stmt->execute($id ? [$id] : null)) {
                return 0;
            }
            return $stmt->fetchAll(\PDO::FETCH_NAMED)[0]['c'];
        } catch (\Throwable $e) {
            self::getInstance()->errors[] = $e;
            return 0;
        }
    }

    /**
     * Executes a query and returns the result
     * @param string $query
     * @param array $bind
     * @return mixed
     */
    public static function query(string $query, array $bind = [])
    {
        try {
            $stmt = self::getInstance()->con->prepare($query);
            if (!$stmt->execute($bind ?: null)) {
                return false;
            }
            return $stmt->fetchAll(\PDO::FETCH_NAMED);
        } catch (\Throwable $e) {
            self::getInstance()->errors[] = $e;
            return false;
        }
    }

    /**
     * Executes a command
     * @param string $query
     * @param array $bind
     * @return bool
     */
    public static function execute(string $query, array $bind = []): bool
    {
        try {
            $stmt = self::getInstance()->con->prepare($query);
            $executed = $stmt->execute($bind ?: null);
            if ($executed) {
                self::getInstance()->autoCommit();
                return true;
            }
            self::getInstance()->rollback();
            return false;
        } catch (\Throwable $e) {
            self::getInstance()->rollback();
            self::getInstance()->errors[] = $e;
            return false;
        }
    }
}
