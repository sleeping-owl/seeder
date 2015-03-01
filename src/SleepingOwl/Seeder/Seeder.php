<?php namespace SleepingOwl\Seeder;

use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use SleepingOwl\Seeder\Lock\Controller;
use SleepingOwl\Seeder\Type\Model;
use SleepingOwl\Seeder\Type\Table;
use SleepingOwl\Seeder\Type\TypeInterface;

class Seeder
{

	/**
	 * @var TypeInterface
	 */
	protected $seeder = null;

	/**
	 * @var DataSeeder
	 */
	protected $dataSeeder = null;

	/**
	 * @var bool
	 */
	protected static $defaultTruncate = false;

	/**
	 * @var bool
	 */
	protected $truncate = null;

	/**
	 * @var int
	 */
	protected static $defaultTimes = 10;

	/**
	 * @var int|null
	 */
	protected $times = null;

	/**
	 * @var string
	 */
	protected static $defaultLocale = 'en_US';

	/**
	 * @var string|null
	 */
	protected $locale = null;

	/**
	 * @var bool
	 */
	protected static $defaultIgnoreExceptions = false;

	/**
	 * @var bool|null
	 */
	protected $ignoreExceptions = null;

	/**
	 * @param TypeInterface $seeder
	 */
	function __construct(TypeInterface $seeder)
	{
		$this->setSeeder($seeder);
		$this->dataSeeder = new DataSeeder;
	}

	/**
	 * @param bool $truncateAll
	 */
	public static function truncateAll($truncateAll = true)
	{
		self::$defaultTruncate = $truncateAll;
	}

	/**
	 * @return TypeInterface
	 */
	public function getSeeder()
	{
		return $this->seeder;
	}

	/**
	 * @param TypeInterface $seeder
	 */
	public function setSeeder(TypeInterface $seeder)
	{
		$this->seeder = $seeder;
	}

	/**
	 * @return string
	 */
	public function getLocale()
	{
		return is_null($this->locale) ? static::$defaultLocale : $this->locale;
	}

	/**
	 * @param string $locale
	 * @return $this
	 */
	public function locale($locale)
	{
		$this->locale = $locale;
		return $this;
	}

	/**
	 * @param string $locale
	 */
	public static function setDefaultLocale($locale = 'en_US')
	{
		static::$defaultLocale = $locale;
	}

	/**
	 * @param int $defaultTimes
	 */
	public static function setDefaultTimes($defaultTimes = 10)
	{
		if ($defaultTimes <= 0)
		{
			throw new \InvalidArgumentException('Times value must be greater than zero');
		}
		static::$defaultTimes = $defaultTimes;
	}

	/**
	 * @return int
	 */
	public function getTimes()
	{
		return is_null($this->times) ? static::$defaultTimes : $this->times;
	}

	/**
	 * @param int $times
	 * @return $this
	 */
	public function times($times)
	{
		if ($times <= 0)
		{
			throw new \InvalidArgumentException('Times value must be greater than zero');
		}
		$this->times = $times;
		return $this;
	}

	/**
	 * @param boolean $defaultIgnoreExceptions
	 */
	public static function setDefaultIgnoreExceptions($defaultIgnoreExceptions)
	{
		static::$defaultIgnoreExceptions = $defaultIgnoreExceptions;
	}

	/**
	 * @return bool|null
	 */
	public function getIgnoreExceptions()
	{
		return is_null($this->ignoreExceptions) ? static::$defaultIgnoreExceptions : $this->ignoreExceptions;
	}

	/**
	 * @param bool|null $ignoreExceptions
	 * @return $this
	 */
	public function ignoreExceptions($ignoreExceptions = true)
	{
		$this->ignoreExceptions = $ignoreExceptions;
		return $this;
	}

	/**
	 * @param string $model
	 * @return static
	 */
	public static function model($model)
	{
		$seeder = new Model($model);
		return new static($seeder);
	}

	/**
	 * @param string $table
	 * @return static
	 */
	public static function table($table)
	{
		$seeder = new Table($table);
		return new static($seeder);
	}

	/**
	 * @param boolean $truncate
	 * @return $this
	 */
	public function truncate($truncate = true)
	{
		$this->truncate = $truncate;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isTruncate()
	{
		return is_null($this->truncate) ? static::$defaultTruncate : $this->truncate;
	}

	/**
	 * @param $value
	 */
	protected function setForeignKeyChecks($value)
	{
		if (($value !== 0) && ($value !== 1))
		{
			$value = 0;
		}
		\DB::statement('SET FOREIGN_KEY_CHECKS=' . $value);
	}

	/**
	 * Run seed
	 */
	protected function run()
	{
		$this->setForeignKeyChecks(0);
		$seeder = $this->getSeeder();
		$table = $seeder->getTable();
		if (Controller::isLocked($table))
		{
			Controller::restore($table);
			$this->writeOutput('<info>Locked:</info> ' . $seeder->getTitle());
		} else
		{
			if ($this->isTruncate())
			{
				$seeder->truncate();
			}
			$this->fill($seeder);
			$this->writeOutput('<info>Seeded:</info> ' . $seeder->getTitle());
		}
		$this->setForeignKeyChecks(1);
	}

	/**
	 * @param $message
	 */
	protected function writeOutput($message)
	{
		$output = app('Symfony\Component\Console\Output\ConsoleOutput');
		$output->writeln($message);
	}

	/**
	 * @param TypeInterface $seeder
	 */
	protected function fill(TypeInterface $seeder)
	{
		for ($i = 0; $i < $this->getTimes(); $i++)
		{
			$data = $this->dataSeeder->getNewRow();
			try
			{
				$seeder->fill($data);
			} catch (QueryException $e)
			{
				if ( ! $this->getIgnoreExceptions())
				{
					throw $e;
				}
			}
		}
	}

	/**
	 * @param \Closure $closure
	 */
	public function seed($closure)
	{
		$this->dataSeeder->setSchemaBuilder($closure);
		$this->dataSeeder->initFaker($this->getLocale());
		$this->run();
	}

}