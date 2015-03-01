<?php namespace SleepingOwl\Seeder\Type;

use Eloquent;

class Model implements TypeInterface
{

	/**
	 * @var string
	 */
	protected $model = null;

	/**
	 * @var Eloquent
	 */
	protected $instance = null;

	/**
	 * @param $model
	 */
	function __construct($model)
	{
		$this->model = $model;
		$this->instance = \App::make($model);
	}

	/**
	 * Truncate data
	 */
	public function truncate()
	{
		$this->instance->all()->each(function ($row)
		{
			$row->delete();
		});
	}

	/**
	 * Fill data
	 * @param array $data
	 */
	public function fill($data)
	{
		Eloquent::unguard();
		$this->instance->create($data);
		Eloquent::reguard();
	}

	/**
	 * Get table name
	 * @return string
	 */
	public function getTable()
	{
		return $this->instance->getTable();
	}

	/**
	 * Get seeder title
	 * @return string
	 */
	public function getTitle()
	{
		return $this->model . ' model';
	}

}