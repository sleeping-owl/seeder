<?php namespace SleepingOwl\Seeder\Console;

use DB;
use Illuminate\Console\Command;
use SleepingOwl\Seeder\Lock\Controller;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class UnlockCommand extends Command
{

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'seeder:unlock';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Unlock the database table seed';

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$table = null;
		$all = $this->option('all');
		if ($all)
		{
			$tables = DB::getDoctrineSchemaManager()->listTableNames();
			$table = implode(',', $tables);
		} else
		{
			$table = $this->argument('table');
		}
		if (is_null($table))
		{
			return $this->error('Provide table name or --all parameter');
		}
		$tables = explode(',', $table);
		foreach ($tables as $table)
		{
			if (Controller::unlock(trim($table)))
			{
				$this->getOutput()->writeln("Table <info>$table</info> was unlocked");
			}
		}
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			[
				'table',
				InputArgument::OPTIONAL,
				'Database table name.'
			],
		];
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			[
				'all',
				null,
				InputOption::VALUE_NONE,
				'Lock all tables.'
			],
		];
	}

}
