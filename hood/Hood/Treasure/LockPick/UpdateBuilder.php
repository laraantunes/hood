<?php
/**
 * 2018 Hood Framework
 */
namespace Hood\Treasure\LockPick;

/**
 * Generates an update SQL
 * @author Maycow Alexandre Antunes <maycow@maycow.com.br>
 * @package Hood\Treasure\LockPick
 */
class UpdateBuilder
{

    /**
     * The table
     * @var string
     */
    protected $table;

    /**
     * The fields to update
     * @var array
     */
    protected $values = [];

    /**
     * The update conditions
     * @var array
     */
    protected $where = [];

    /**
     * The fields types
     * @var array
     */
    protected $types = [];

    /**
     *The conditions types
     * @var array
     */
    protected $typeWhere = [];

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
     * Returns a new UpdateBuilder object
     */
    public static function factory() {
        return new self();
    }

    /**
     * Sets the table
     * @param string $table
     * @return \Hood\Treasure\LockPick\UpdateBuilder The current object
     */
    public function table($table) {
        $this->table = $table;
        return $this;
    }

    /**
     * Returns the table
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Adds a field on object
     * @param string $field
     * @param string $value
     * @param string type Field type, default "string"
     * @return \Hood\Treasure\LockPick\UpdateBuilder The current object
     */
    public function value($field, $value, $type = self::TYPE_STRING) {
        $this->values[$field] = $value;
        $this->types[$field] = $type;
        return $this;
    }

    /**
     * Adds a condition
     * @param string $condition
     * @param string $value
     * @param string $type
     * @return \Hood\Treasure\LockPick\UpdateBuilder The current object
     */
    public function where($condition, $value = null, $type = self::TYPE_STRING) {
        $this->where[$condition] = $value;
        $this->typeWhere[$condition] = $type;
        return $this;
    }

    /**
     * Returns a field's value
     * @param string $field
     * @return mixed
     *
     */
    public function getValue($field) {
        return $this->values[$field];
    }

    /**
     * Returns a field's type
     * @param string $field
     * @return string
     *
     */
    public function getType($field) {
        return $this->types[$field];
    }

    /**
     * Generates the update SQL and returns
     */
    public function __toString() {
        return $this->build();
    }

    /**
     * Generates the update SQL
     * @param boolean $closeQuery If true, closes the update string with a ;
     * @return string
     */
    public function build($closeQuery = false) {

        $update = '';
        if ($this->prepend != '') {
            $update .= "\n{$this->prepend}\n";
        }

        $update .= "UPDATE {$this->table} SET";

        $valuesString = '';
        foreach ($this->values as $key => $value) {
            if ($value === null) {
                $valuesString .= "{$key} = null,";
            } elseif ($this->types[$key] == 'string') {
                $valuesString .= "{$key} = '{$value}',";
            } else {
                $valuesString .= "{$key} = {$value},";
            }
        }

        if (substr($valuesString, -1) == ',') {
            $valuesString = substr($valuesString, 0, strlen($valuesString) - 1);
        }
        $update .= " {$valuesString}";

        if (count($this->where) > 0) {
            $whereString = ' WHERE ';

            foreach ($this->where as $key => $value) {
                if ($value === null) {
                    $whereString .= "{$key} and ";
                } elseif ($this->typeWhere[$key] == 'string') {
                    $whereString .= "{$key} = '{$value}' and ";
                } else {
                    $whereString .= "{$key} = {$value} and ";
                }
            }

            if (substr($whereString, -4) == 'and ') {
                $whereString = substr($whereString, 0, strlen($whereString) - 4);
            }
            $update .= " {$whereString}".($closeQuery ? ';' : '')."\n";
        } else {
            $update .= ";\n";
        }

        if ($this->append != '') {
            $update .= "\n{$this->append}\n";
        }

        return $update;
    }

    /**
     * Adds a code after build
     * @param string|callable $callback if $callback is a string,
     * insets the text after build the update string. If it's a
     * function, executes the function passing the current object
     * as a parameter and inserts the function's return after build
     * the update string
     * @return \Hood\Treasure\LockPick\UpdateBuilder The current object
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
     * insets the text before build the update string. If it's a
     * function, executes the function passing the current object
     * as a parameter and inserts the function's return before build
     * the update string
     * @return \Hood\Treasure\LockPick\UpdateBuilder The current object
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
