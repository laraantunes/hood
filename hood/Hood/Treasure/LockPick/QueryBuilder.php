<?php
/**
 * 2018 Hood Framework
 */

namespace Hood\Treasure\LockPick;

use \Hood\Exceptions\QueryBuilderException;

/**
 * Builder for SQL Queries
 * @author Maycow Alexandre Antunes <maycow@maycow.com.br>
 * @package Hood\Treasure\LockPick
 */
class QueryBuilder{

    /**
     * Fields to query
     * @var array
     */
    protected $fields = [];

    /**
     * Main table for the query
     * @var string
     */
    protected $table = "";

    /**
     * Tables to join on query. Uses the following pattern:
     * [][table]
     * [][condition]
     * [][type]
     *
     * @var array
     */
    protected $join = [];

    /**
     * Conditions of the query. Uses the following pattern:
     * [][operator]
     * [][condition]
     * @var array
     */
    protected $where = [];

    /**
     * Order By Fields
     * @var array
     */
    protected $order = [];

    /**
     * Group By Fields
     * @var array
     */
    protected $group = [];

    /**
     * MySQL limit expression (limit [0],[1])
     * @var string
     */
    protected $limit = [];

    /**
     * Returns a new QueryBuilder object
     */
    public static function factory() {
        return new self();
    }

    /**
     * Adds a field on query
     * @param string $field The field
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function field($field)
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Includes all the fields on query
     * @param array $fields An array with all the fields
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function fields($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Sets the table
     * @param string $table The table
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Adds a join on the query
     * @param string $table The table
     * @param string $condition The join condition
     * @param string|bool $type The join type (inner/left/right)
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function join($table, $condition, $type = false)
    {
        $join = array("table" => $table,
                      "condition" => $condition,
                      "type" => $type);

        $this->join[] = $join;
        return $this;
    }

    /**
     * Adds a condition to the query
     * @param string $condition Condition
     * @param string|bool $operator Operator (and/or)
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function where($condition, $operator = false)
    {
        $where = array("condition" => $condition, "operator" => $operator);
        $this->where[] = $where;
        return $this;
    }

    /**
     * Adds an order by field
     * @param string $order The field
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function orderByField($order)
    {
        $this->order[] = $order;
        return $this;
    }

    /**
     * Adds all the order by fields
     * @param array $order the array with all the order by fields
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function orderBy($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Adds a field to group by array
     * @param string $group The field
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function groupByField($group)
    {
        $this->group[] = $group;
        return $this;
    }

    /**
     * sets the group by array
     * @param array $group The array with all the group by fields
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function groupBy($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Sets the MySQL limit values
     * @param int $limit1 First limit value
     * @param int $limit2 Second limit value
     * @return \Hood\Treasure\Lockpick\QueryBuilder The current object
     */
    public function setLimit($limit1, $limit2 = null)
    {
        $this->limit = array($limit1, $limit2);
        return $this;
    }

    /**
     * Generates the query
     * @return string The Query generated
     * @throws QueryBuilderException
     */
    public function build()
    {
        $sql = "SELECT ";

        /* Campos */
        if(!$this->fields){
            throw new QueryBuilderException("The fields are necessary to generate the query.");
        }else{
            $first = true;
            foreach($this->fields as $fields){
                $sql .= (($first)?"":", ").$fields;
                $first = false;
            }
        }

        /* Main Table */
        if(!$this->table){
            throw new QueryBuilderException("A table is necessary to generate the query.");
        }else{
            $sql .= " FROM ".$this->table." ";
        }

        /* Join */
        if($this->join){
            foreach($this->join as $join){
                $sql .= " ".$join['type']." JOIN ".$join['table']." ON ".$join['condition']." ";
            }
        }

        /* Where */
        if($this->where){
            $first = true;
            foreach($this->where as $where){
                if(!$where['operator'] && !$first){
                    throw new QueryBuilderException(
                        "An operator for the condition '".$where['condition']."' is necessary."
                    );
                }
                if($where['condition']){
                    $sql .= (($first)?" WHERE ".$where['condition']." ":$where['operator']." ".$where['condition']." ");
                    $first = false;
                }
            }
        }

        /* Group By */
        if($this->group){
            $sql .= " GROUP BY ";
            $first = true;
            foreach($this->group as $group){
                $sql .= (($first)?"":", ").$group;
                $first = false;
            }
        }

        /* Order by */
        if($this->order){
            $sql .= " ORDER BY ";
            $first = true;
            foreach($this->order as $order){
                $sql .= (($first)?"":", ").$order;
                $first = false;
            }
        }

        if($this->limit){
            if(!$this->limit[0]){
                throw new QueryBuilderException("An initial limit is necessary.");
            }else{
                $sql .= " LIMIT ".$this->limit[0] . (($this->limit[1])?", ".$this->limit[1]:"");
            }
        }

        return $sql;
    }

    /**
     * Build and returns the generated query
     * @return string The generated query
     * @throws QueryBuilderException
     */
    public function __toString()
    {
        return $this->build();
    }
}
