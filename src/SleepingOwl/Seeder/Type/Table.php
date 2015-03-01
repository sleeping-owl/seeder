<?php namespace SleepingOwl\Seeder\Type;

use DB;

class Table implements TypeInterface
{

	/**
	 * @var string
	 */
	protected $table = null;

	/**
	 * @var DB
	 */
	protected $instance = null;

	/**
	 * @param $table
	 */
	function __construct($table)
	{
		$this->table = $table;
		$this->instance = DB::table($table);
	}

	/**
	 * Truncate data
	 */
	public function truncate()
	{
		$this->instance->delete();
	}

	/**
	 * Fill data
	 * @param array $data
	 */
	public function fill($data)
	{
		$this->instance->insert($data);
	}

	/**
	 * Get table name
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * Get seeder title
	 * @return string
	 */
	public function getTitle()
	{
		return $this->table . ' table';
	}

}