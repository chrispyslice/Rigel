<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Core;

class Model_Crud extends \Model implements \Iterator, \ArrayAccess {

	/**
	 * @var  string  $_table_name  The table name (must set this in your Model)
	 */
	// protected static $_table_name = '';

	/**
	 * @var  string  $_primary_key  The primary key for the table
	 */
	// protected static $_primary_key = 'id';

	/**
	 * @var string   $_connection   The database connection to use
	 */
	// protected static $_connection = null;

	/**
	 * @var  array  $_rules  The validation rules (must set this in your Model to use)
	 */
	// protected static $_rules = array();

	/**
	 * @var array  $_labels  Field labels (must set this in your Model to use)
	 */
	// protected static $_labels = array();

	/**
	 * @var array  $_defaults  Field defaults (must set this in your Model to use)
	 */
	// protected static $_defaults = array();

	/**
	 * Forges new Model_Crud objects.
	 *
	 * @param   array  $data  Model data
	 * @return  Model_Crud
	 */
	public static function forge(array $data = array())
	{
		return new static($data);
	}

	/**
	 * Finds a row with the given primary key value.
	 *
	 * @param   mixed  $value  The primary key value to find
	 * @return  null|object  Either null or a new Model object
	 */
	public static function find_by_pk($value)
	{
		return static::find_one_by(static::primary_key(), $value);
	}

	/**
	 * Finds a row with the given column value.
	 *
	 * @param   mixed  $column  The column to search
	 * @param   mixed  $value   The value to find
	 * @return  null|object  Either null or a new Model object
	 */
	public static function find_one_by($column, $value = null, $operator = '=')
	{
		$config = array(
			'limit' => 1,
		);

		if (is_array($column))
		{
			$config['where'] = $column;
		}
		else
		{
			$config['where'] = array(array($column, $operator, $value));
		}

		$result = static::find($config);

		if ($result !== null)
		{
			return current($result);
		}

		return null;
	}

	/**
	 * Finds all records where the given column matches the given value using
	 * the given operator ('=' by default).  Optionally limited and offset.
	 *
	 * @param   string  $column    The column to search
	 * @param   mixed   $value     The value to find
	 * @param   string  $operator  The operator to search with
	 * @param   int     $limit     Number of records to return
	 * @param   int     $offset    What record to start at
	 * @return  null|object  Null if not found or an array of Model object
	 */
	public static function find_by($column = null, $value = null, $operator = '=', $limit = null, $offset = 0)
	{
		$config = array(
			'limit' => $limit,
			'offset' => $offset,
		);

		if ($column !== null)
		{
			if (is_array($column))
			{
				$config['where'] = $column;
			}
			else
			{
				$config['where'] = array(array($column, $operator, $value));
			}
		}

		return static::find($config);
	}

	/**
	 * Finds all records in the table.  Optionally limited and offset.
	 *
	 * @param   int     $limit     Number of records to return
	 * @param   int     $offset    What record to start at
	 * @return  null|object        Null if not found or an array of Model object
	 */
	public static function find_all($limit = null, $offset = 0)
	{
		return static::find(array(
			'limit' => $limit,
			'offset' => $offset,
		));
	}

	/**
	 * Finds all records.
	 *
	 * @param    array     $config     array containing query settings
	 * @param    string    $key        optional array index key
	 * @return   array|null            an array containing models or null if none are found
	 */
	public static function find($config = array(), $key = null)
	{
		$query = \DB::select()
			->from(static::$_table_name)
			->as_object(get_called_class());

		$config = $config + array(
			'select' => array('*'),
			'where' => array(),
			'order_by' => array(),
			'limit' => null,
			'offset' => 0,
		);

		extract($config);

		is_string($select) and $select = array($select);
		$query->select_array($select);

		if ( ! empty($where))
		{
			$query->where($where);
		}

		foreach ($order_by as $_field => $_direction)
		{
			$query->order_by($_field, $_direction);
		}

		if ($limit !== null)
		{
			$query = $query->limit($limit)->offset($offset);
		}

		$query = static::pre_find($query);

		$result =  $query->execute(isset(static::$_connection) ? static::$_connection : null);
		$result = ($result->count() === 0) ? null : $result->as_array($key);

		return static::post_find($result);
	}

	/**
	 * Implements dynamic Model_Crud::find_by_{column} and Model_Crud::find_one_by_{column}
	 * methods.
	 *
	 * @param   string  $name  The method name
	 * @param   string  $args  The method args
	 * @return  mixed   Based on static::$return_type
	 * @throws  BadMethodCallException
	 */
	public static function __callStatic($name, $args)
	{
		if (strncmp($name, 'find_by_', 8) === 0)
		{
			return static::find_by(substr($name, 8), reset($args));
		}
		elseif (strncmp($name, 'find_one_by_', 12) === 0)
		{
			return static::find_one_by(substr($name, 12), reset($args));
		}
		throw new \BadMethodCallException('Method "'.$name.'" does not exist.');
	}

	/**
	 * Get the primary key for the current Model
	 *
	 * @return  string
	 */
	protected static function primary_key()
	{
		return isset(static::$_primary_key) ? static::$_primary_key : 'id';
	}

	/**
	 * Gets called before the query is executed.  Must return the query object.
	 *
	 * @param   Database_Query  $query  The query object
	 * @return  Database_Query
	 */
	protected static function pre_find($query)
	{
		return $query;
	}

	/**
	 * Gets called after the query is executed and right before it is returned.
	 * $result will be null if 0 rows are returned.
	 *
	 * @param   array|null    $result    the result array or null when there was no result
	 * @return  array|null
	 */
	protected static function post_find($result)
	{
		return $result;
	}

	/**
	 * @var  bool  $_is_new  If this is a new record
	 */
	protected $_is_new = true;

	/**
	 * @var  bool  $_is_frozen  If this is a record is frozen
	 */
	protected $_is_frozen = false;

	/**
	 * @var  object  $_validation  The validation instance
	 */
	protected $_validation = null;

	/**
	 * Sets up the object.
	 *
	 * @param   array  $data  The data array
	 * @return  void
	 */
	public function __construct(array $data = array())
	{
		if (isset($this->{static::primary_key()}))
		{
			$this->is_new(false);
		}

		if ( ! empty($data))
		{
			foreach ($data as $key => $value)
			{
				$this->{$key} = $value;
			}
		}
	}

	/**
	 * Magic setter so new objects can be assigned values
	 *
	 * @param   string  $property  The property name
	 * @param   mixed   $value     The property value
	 * @return  void
	 */
	public function __set($property, $value)
	{
		$this->{$property} = $value;
	}

	/**
	 * Sets an array of values to class properties
	 *
	 * @param   array  $data  The data
	 * @return  $this
	 */
	public function set(array $data)
	{
		foreach ($data as $key => $value)
		{
			$this->{$key} = $value;
		}
		return $this;
	}

	/**
	 * Saves the object to the database by either creating a new record
	 * or updating an existing record. Sets the default values if set.
	 *
	 * @return  mixed  Rows affected and or insert ID
	 */
	public function save()
	{
		if ($this->frozen())
		{
			throw new \Exception('Cannot modify a frozen row.');
		}

		$vars = $this->prep_values($this->to_array());

		// Set default if there are any
		isset(static::$_defaults) and $vars = $vars + static::$_defaults;

		if (isset(static::$_rules) and count(static::$_rules) > 0)
		{
			$validated = $this->run_validation($vars);

			if ($validated)
			{
				$vars = $this->validation()->validated() + $vars;
			}
			else
			{
				return false;
			}
		}

		if ($this->is_new())
		{
			$query = \DB::insert(static::$_table_name)
			            ->set($vars);

			$query = $this->pre_save($query);
			$result = $query->execute(isset(static::$_connection) ? static::$_connection : null);
			$result[1] > 0 and $this->set($vars);
			$this->{static::primary_key()} = $result[0];

			return $this->post_save($result);
		}

		$query = \DB::update(static::$_table_name)
		         ->set($vars)
		         ->where(static::primary_key(), '=', $this->{static::primary_key()});

		$query = $this->pre_update($query);
		$result = $query->execute(isset(static::$_connection) ? static::$_connection : null);
		$result[1] > 0 and $this->set($vars);

		return $this->post_update($result);
	}

	/**
	 * Deletes this record and freezes the object
	 *
	 * @return  mixed  Rows affected
	 */
	public function delete()
	{
		$this->frozen(true);
		$query = \DB::delete(static::$_table_name)
		            ->where(static::primary_key(), '=', $this->{static::primary_key()});

		$query = $this->pre_delete($query);
		$result = $query->execute(isset(static::$_connection) ? static::$_connection : null);

		return $this->post_delete($result);
	}

	/**
	 * Either checks if the record is new or sets whether it is new or not.
	 *
	 * @param   bool|null  $new  Whether this is a new record
	 * @return  bool|$this
	 */
	public function is_new($new = null)
	{
		if ($new === null)
		{
			return $this->_is_new;
		}

		$this->_is_new = (bool) $new;

		return $this;
	}

	/**
	 * Either checks if the record is frozen or sets whether it is frozen or not.
	 *
	 * @param   bool|null  $new  Whether this is a frozen record
	 * @return  bool|$this
	 */
	public function frozen($frozen = null)
	{
		if ($frozen === null)
		{
			return $this->_is_frozen;
		}

		$this->_is_frozen = (bool) $frozen;

		return $this;
	}

	/**
	 * Returns the a validation object for the model.
	 *
	 * @return  object  Validation object
	 */
	public function validation()
	{
		$this->_validation or $this->_validation = \Validation::forge(md5(microtime(true)));

		return $this->_validation;
	}

	/**
	 * Returns all of $this object's public properties as an associative array.
	 *
	 * @return  array
	 */
	public function to_array()
	{
		return get_object_public_vars($this);
	}

	/**
	 * Implementation of the Iterator interface
	 */

	protected $_iterable = array();

	public function rewind()
	{
		$this->_iterable = $this->to_array();
		reset($this->_iterable);
	}

	public function current()
	{
		return current($this->_iterable);
	}

	public function key()
	{
		return key($this->_iterable);
	}

	public function next()
	{
		return next($this->_iterable);
	}

	public function valid()
	{
		return key($this->_iterable) !== null;
	}

	/**
	 * Sets the value of the given offset (class property).
	 *
	 * @param   string  $offset  class property
	 * @param   string  $value   value
	 * @return  void
	 */
	public function offsetSet($offset, $value)
	{
		$this->{$offset} = $value;
	}

	/**
	 * Checks if the given offset (class property) exists.
	 *
	 * @param   string  $offset  class property
	 * @return  bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->{$offset});
	}

	/**
	 * Unsets the given offset (class property).
	 *
	 * @param   string  $offset  class property
	 * @return  void
	 */
	public function offsetUnset($offset)
	{
		unset($this->{$offset});
	}

	/**
	 * Gets the value of the given offset (class property).
	 *
	 * @param   string  $offset  class property
	 * @return  mixed
	 */
	public function offsetGet($offset)
	{
		if (isset($this->{$offset}))
		{
			return $this->{$offset};
		}

		throw new \OutOfBoundsException('Property "'.$offset.'" not found for '.get_called_class().'.');
	}

	/**
	 * Run validation
	 *
	 * @param   array  $vars  array to validate
	 * @return  bool   validation result
	 */
	protected function run_validation($vars)
	{
		if ( ! isset(static::$_rules))
		{
			return true;
		}

		$this->_validation = null;
		$this->_validation = $this->validation();

		foreach (static::$_rules as $field => $rules)
		{
			$label = (isset(static::$_labels) and array_key_exists($field, static::$_labels)) ? static::$_labels[$field] : $field;
			$this->_validation->add_field($field, $label, $rules);
		}

		$vars = $this->pre_validate($vars);

		$result = $this->_validation->run($vars);

		return $this->post_validate($result);
	}

	/**
	 * Gets called before the insert query is executed.  Must return
	 * the query object.
	 *
	 * @param   Database_Query  $query  The query object
	 * @return  Database_Query
	 */
	protected function pre_save($query)
	{
		return $query;
	}

	/**
	 * Gets called after the insert query is executed and right before
	 * it is returned.
	 *
	 * @param   array  $result  insert id and number of affected rows
	 * @return  array
	 */
	protected function post_save($result)
	{
		return $result;
	}

	/**
	 * Gets called before the update query is executed.  Must return the query object.
	 *
	 * @param   Database_Query  $query  The query object
	 * @return  Database_Query
	 */
	protected function pre_update($query)
	{
		return $query;
	}

	/**
	 * Gets called after the update query is executed and right before
	 * it is returned.
	 *
	 * @param   int  $result  Number of affected rows
	 * @return  int
	 */
	protected function post_update($result)
	{
		return $result;
	}

	/**
	 * Gets called before the delete query is executed.  Must return the query object.
	 *
	 * @param   Database_Query  $query  The query object
	 * @return  Database_Query
	 */
	protected function pre_delete($query)
	{
		return $query;
	}

	/**
	 * Gets called after the delete query is executed and right before
	 * it is returned.
	 *
	 * @param   int  $result  Number of affected rows
	 * @return  int
	 */
	protected function post_delete($result)
	{
		return $result;
	}

	/**
	 * Gets called before the validation is ran.
	 *
	 * @param   array  $data  The validation data
	 * @return  array
	 */
	protected function pre_validate($data)
	{
		return $data;
	}

	/**
	 * Called right after the validation is ran.
	 *
	 * @param   bool  $result  Validation result
	 * @return  bool
	 */
	protected function post_validate($result)
	{
		return $result;
	}

	/**
	 * Called right after values retrieval, before save,
	 * update, setting defaults and validation.
	 *
	 * @param   array  $values  input array
	 * @return  array
	 */
	protected function prep_values($values)
	{
		return $values;
	}

}