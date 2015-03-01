<?php namespace SleepingOwl\Seeder\Type;

interface TypeInterface
{

	/**
	 * Truncate data
	 */
	public function truncate();

	/**
	 * Fill data
	 * @param array $data
	 */
	public function fill($data);

	/**
	 * Get table name
	 * @return string
	 */
	public function getTable();

	/**
	 * Get seeder title
	 * @return string
	 */
	public function getTitle();

}