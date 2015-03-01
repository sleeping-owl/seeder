<?php namespace SleepingOwl\Seeder;

use Illuminate\Support\ServiceProvider;

class SeederServiceProvider extends ServiceProvider
{

	/**
	 * Register the service provider.
	 * @return void
	 */
	public function register()
	{
		$this->commands('\SleepingOwl\Seeder\Console\LockCommand');
		$this->commands('\SleepingOwl\Seeder\Console\UnlockCommand');
	}

	/**
	 * @return array
	 */
	public function provides()
	{
		return ['seeder'];
	}

}