<?php namespace SleepingOwl\Seeder;

use SleepingOwl\Seeder\Field\Field;
use \Faker\Factory as Faker;

class DataSeeder
{

	/**
	 * @var \Closure null
	 */
	protected $schemaBuilder = null;

	/**
	 * @var Field[]
	 */
	protected $fields = [];

	/**
	 * @var Faker
	 */
	protected $faker = null;

	/**
	 * @return callable
	 */
	public function getSchemaBuilder()
	{
		return $this->schemaBuilder;
	}

	/**
	 * @param callable $schemaBuilder
	 */
	public function setSchemaBuilder($schemaBuilder)
	{
		$this->schemaBuilder = $schemaBuilder;
		$this->getSchema();
	}

	/**
	 *
	 */
	protected function getSchema()
	{
		$this->fields = [];
		$schemaBuilder = $this->schemaBuilder;
		$schemaBuilder($this);
	}

	/**
	 * @return array
	 */
	public function getNewRow()
	{
		$data = [];
		foreach ($this->fields as $field => $fieldObject)
		{
			$data[$field] = $fieldObject->generateNew($this->faker);
		}
		return $data;
	}

	/**
	 * @param $locale
	 */
	public function initFaker($locale)
	{
		$this->faker = Faker::create($locale);
		$this->faker->addProvider(new \SleepingOwl\Seeder\Provider\Seeder($this->faker));
	}

	/**
	 * @param $field
	 * @return Field
	 */
	public function field($field)
	{
		$fieldObject = new Field($field);
		$this->fields[$field] = $fieldObject;
		return $fieldObject;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return Field
	 */
	function __call($name, $arguments)
	{
		return $this->field($name);
	}

	/**
	 * @param $name
	 * @return Field
	 */
	function __get($name)
	{
		return $this->field($name);
	}

} 