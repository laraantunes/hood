<?php
/**
 * 2018 Hood Framework
 */
namespace Hood\Treasure\LockPick;

/**
 * Generates an insert SQL
 * @author Maycow Alexandre Antunes <maycow@maycow.com.br>
 * @package Hood\Treasure\LockPick
 */
class InsertBuilder
{

    /**
     * The table
     * @var string
     */
    protected $table;

    /**
     * The fields to insert
     * @var array
     */
    protected $values = [];

    /**
     * The fields types
     * @var array
     */
    protected $types = [];

    /**
     * The result of appendToBuild() method
     * @var string
     */
    protected $append = '';

    /**
     * The result of prependToBuild() method
     * @var string
     */
    protected $prepend = '';

    /**
     * Text field type (string, date, etc)
     * @var string
     */
    const TYPE_STRING = 'string';

    /**
     * Numeric field type
     * @var string
     */
    const TYPE_NUMBER = 'number';

    /**
     * Bind field type
     * @var string
     */
    const TYPE_BIND = 'bind';

    /**
     * Returns a new InsertBuilder object
     */
    public static function factory()
    {
        return new self();
    }

    /**
     * Sets the table
     * @param string $table
     * @return \Hood\Treasure\LockPick\InsertBuilder The current object
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the table
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Adds a field on object
     * @param string $field
     * @param string $value
     * @param string type Field type, default "string"
     * @return \Hood\Treasure\LockPick\InsertBuilder The current object
     */
    public function value($field, $value, $type = self::TYPE_STRING)
    {
        $this->values[$field] = $value;
        $this->types[$field] = $type;
        return $this;
    }

    /**
     * Returns a field's value
     * @param string $field
     * @return mixed
     *
     */
    public function getValue($field)
    {
        return $this->values[$field];
    }

    /**
     * Returns a field's type
     * @param string $field
     * @return string
     *
     */
    public function getType($field)
    {
        return $this->types[$field];
    }

    /**
     *  Generates the insert SQL and returns
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     *  Generates the insert SQL
     * @param boolean $closeQuery If true, closes the insert string with a ;
     * @return string
     */
    public function build($closeQuery = false) {

        $insert = '';
        if ($this->prepend != '') {
            $insert .= "\n{$this->prepend}\n";
        }

        $fields = array_keys($this->values);
        $fieldsString = implode($fields, ',');
        if (substr($fieldsString, -1) == ',') {
            $fieldsString = substr($fieldsString, 0, strlen($fieldsString) - 1);
        }
        $insert .= "INSERT INTO {$this->table} ({$fieldsString}) VALUES (";

        $valuesString = '';
        foreach ($this->values as $key => $value) {
            if ($value === null) {
                $valuesString .= "null,";
            } elseif ($this->types[$key] == 'string') {
                $valuesString .= "'{$value}',";
            } else {
                $valuesString .= "{$value},";
            }
        }

        if (substr($valuesString, -1) == ',') {
            $valuesString = substr($valuesString, 0, strlen($valuesString) - 1);
        }
        $insert .= "{$valuesString})".($closeQuery ? ';' : '')."\n";

        if ($this->append != '') {
            $insert .= "\n{$this->append}\n";
        }

        return $insert;
    }

    /**
     * Adds a code after build
     * @param string|callable $callback if $callback is a string,
     * insets the text after build the insert string. If it's a
     * function, executes the function passing the current object
     * as a parameter and inserts the function's return after build
     * the insert string
     * @return \Hood\Treasure\LockPick\InsertBuilder The current object
     */
    public function appendToBuild($callback) {
        if (is_callable($callback)) {
            $this->append = $callback($this);
        } else {
            $this->append = $callback;
        }
        return $this;
    }

    /**
    * Adds a code before build
    * @param string|callable $callback if $callback is a string,
    * insets the text before build the insert string. If it's a
    * function, executes the function passing the current object
    * as a parameter and inserts the function's return before build
    * the insert string
     * @return \Hood\Treasure\LockPick\InsertBuilder The current object
     */
    public function prependToBuild($callback) {
        if (is_callable($callback)) {
            $this->prepend = $callback($this);
        } else {
            $this->prepend = $callback;
        }
        return $this;
    }

}
