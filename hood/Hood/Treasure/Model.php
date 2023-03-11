<?php

namespace Hood\Treasure;

/**
 * Class Model
 * @package Hood\Treasure
 */
class Model implements \ArrayAccess
{
    /**
     * The table related to the model
     * @var string
     */
    public static $table = "table";

    /**
     * The relation between model attributes and table columns
     * @var array
     */
    public static $fields = [];

    /**
     * The table's primary key
     * @var string
     */
    public static $primaryKey = 'id';

    /**
     * Returns the model's table
     * @return string
     */
    public static function getTable()
    {
        return static::$table;
    }

    /**
     * Returns the model's relation between attributes and table columns
     * @return array
     */
    public static function getFields()
    {
        return static::$fields;
    }

    /**
     * Returns the table's primary key
     * @return string
     */
    public static function getPrimaryKey()
    {
        return static::$primaryKey;
    }

    /**
     * Check if attribute exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Gets an attribute value
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Sets an attribute value
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unset an attribute
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * Callback triggered before load
     */
    public function beforeLoad(){}

    /**
     * Callback triggered before save
     */
    public function beforeSave(){}

    /**
     * Callback triggered before update
     */
    public function beforeUpdate(){}

    /**
     * Callback triggered before insert
     */
    public function beforeInsert(){}

    /**
     * Callback triggered before delete
     */
    public function beforeDelete(){}

    /**
     * Callback triggered after load
     */
    public function afterLoad(){}

    /**
     * Callback triggered after save
     */
    public function afterSave(){}

    /**
     * Callback triggered after update
     */
    public function afterUpdate(){}

    /**
     * Callback triggered after insert
     */
    public function afterInsert(){}

    /**
     * Callback triggered after delete
     */
    public function afterDelete(){}

    /**
     * Callback triggered after save success
     */
    public function afterSaveSuccess(){}

    /**
     * Callback triggered after update success
     */
    public function afterUpdateSuccess(){}

    /**
     * Callback triggered after insert success
     */
    public function afterInsertSuccess(){}

    /**
     * Callback triggered after delete success
     */
    public function afterDeleteSuccess(){}

    /**
     * Callback triggered after save fail
     */
    public function afterSaveFail(){}

    /**
     * Callback triggered after update fail
     */
    public function afterUpdateFail(){}

    /**
     * Callback triggered after insert fail
     */
    public function afterInsertFail(){}

    /**
     * Callback triggered after delete fail
     */
    public function afterDeleteFail(){}

    /**
     * Returns an object of the current type
     * @return Model
     */
    public static function factory(): self
    {
        $class = get_called_class();
        return new $class();
    }

    /**
     * Saves the current object
     * @return bool
     */
    public function save(): bool
    {
        $primaryKey = self::getPrimaryKey();
        $count = Chest::count($this::getTable(), $this->$primaryKey);
        if ($count == 1) {
            return $this->update();
        }
        return $this->insert();
    }

    /**
     * Updates the current object
     * @return bool
     */
    public function update(): bool
    {
        $primaryKey = self::getPrimaryKey();
        return Chest::update($this, [], $this->$primaryKey);
    }

    /**
     * Inserts the current object
     * @return bool
     */
    public function insert(): bool
    {
        return Chest::insert($this, self::getFields());
    }

    /**
     * Deletes the current object
     * @return bool
     */
    public function delete(): bool
    {
        $primaryKey = self::getPrimaryKey();
        return Chest::delete($this, $this->$primaryKey);
    }

    /**
     * Gets an Model object based on id
     * @param $id
     * @return self|bool
     */
    public static function find($id)
    {
        $class = get_called_class();
        $obj = new $class();
        return Chest::get($obj, [], $id)[0] ?? false;
    }

    /**
     * Gets all the entries of the current object
     * @return array
     */
    public static function all(): array
    {
        $class = get_called_class();
        $obj = new $class();
        return Chest::get($obj) ?? [];
    }
}
