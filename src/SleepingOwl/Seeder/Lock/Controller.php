<?php namespace SleepingOwl\Seeder\Lock;

use DB;
use File;

class Controller
{

	/**
	 * @param $table
	 */
	public static function lock($table)
	{
		$rows = DB::table($table)->get();
		$data = json_encode($rows);
		static::writeLock($table, $data);
	}

	/**
	 * @param $table
	 * @return bool
	 */
	public static function unlock($table)
	{
		$path = static::getPath($table);
		return File::delete($path);
	}

	/**
	 * @param $table
	 * @param $data
	 */
	public static function writeLock($table, $data)
	{
		$path = static::getPath($table);
		File::makeDirectory(dirname($path), 0755, true, true);
		File::put($path, $data);
	}

	/**
	 * @param $table
	 * @return string
	 */
	public static function getPath($table)
	{
		return storage_path('seed_lock/' . $table . '.json');
	}

	/**
	 * @param $table
	 * @return bool
	 */
	public static function isLocked($table)
	{
		$path = static::getPath($table);
		return File::exists($path);
	}

	/**
	 * @param $table
	 */
	public static function restore($table)
	{
		$path = static::getPath($table);
		$json = File::get($path);
		$data = json_decode($json, true);
		static::truncate($table);
		static::fill($table, $data);
	}

	/**
	 * @param $table
	 * @param $data
	 */
	public static function fill($table, $data)
	{
		$table = DB::table($table);
		$table->insert($data);
	}

	/**
	 * @param $table
	 */
	public static function truncate($table)
	{
		DB::table($table)->delete();
	}

} 