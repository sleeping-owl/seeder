<?php namespace SleepingOwl\Seeder\Provider;

class Seeder extends \Faker\Provider\Base
{

	/**
	 * @param $model
	 * @return mixed
	 * @throws \Exception
	 */
	public static function anyOf($model)
	{
		$instance = \App::make($model);
		$count = $instance->count();
		if ($count <= 0)
		{
			throw new \Exception("Model [$model] has no entities");
		}

		$offset = mt_rand(0, $count - 1);
		return $instance->offset($offset)->limit(1)->first()->getKey();
	}

	/**
	 * @param $closure
	 * @return mixed
	 */
	public static function call($closure)
	{
		return $closure();
	}

}
