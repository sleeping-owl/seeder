## Package to create simple seeders with ability to lock/unlock tables

[![Latest Stable Version](https://poser.pugx.org/sleeping-owl/seeder/v/stable.svg)](https://packagist.org/packages/sleeping-owl/with-join)
[![License](https://poser.pugx.org/sleeping-owl/seeder/license.svg)](https://packagist.org/packages/sleeping-owl/with-join)

## Overview

This package will allow you to write simple seeders:

```php
use SleepingOwl\Seeder\DataSeeder;
use SleepingOwl\Seeder\Seeder as SleepingOwlSeeder;

class DatabaseSeeder extends Seeder
{

	public function run()
	{
		 # set global locale (default is en_US)
		SleepingOwlSeeder::setDefaultLocale('de_DE');
		
		 # set global entries count (default is 10)
		SleepingOwlSeeder::setDefaultTimes(10);
		
		 # truncate all tables before seeding (default is off)
		SleepingOwlSeeder::truncateAll();

		# seed Country model
		SleepingOwlSeeder::model(Country::class)
			->seed(function (DataSeeder $schema)
			{
				$schema->title->unique()->country;
			});

		# seed Company model
		SleepingOwlSeeder::model(Company::class)
			->seed(function (DataSeeder $schema)
			{
				$schema->title->unique()->company;
				$schema->address->streetAddress;
				$schema->phone->phoneNumber;
			});

		# seed Contact model
		SleepingOwlSeeder::model(Contact::class)
			->seed(function (DataSeeder $schema)
			{
				$schema->firstName->firstName;
				$schema->lastName->lastName;
				$schema->birthday->dateTimeThisCentury;
				$schema->phone->phoneNumber;
				$schema->address->address;
				$schema->country_id->optional(0.9)->anyOf(Country::class);
				$schema->comment->paragraph(5);
			});

		# seed company_contact table
		SleepingOwlSeeder::table('company_contact')
			->ignoreExceptions()
			->seed(function ($schema)
			{
				$schema->company_id->anyOf(Company::class);
				$schema->contact_id->anyOf(Contact::class);
			});
	}

}
```

And adds 2 new commands:

1. `php artisan seeder:lock <table> --all` &mdash; lock table from any changes (table data will be saved and restored after reseeding).

2. `php artisan seeder:unlock <table> --all` &mdash; unlock table for random seeding.

## Installation

 1. Require this package in your composer.json and run composer update (or run `composer require sleeping-owl/seeder:1.x` directly):

		"sleeping-owl/seeder": "1.*"

 2. After composer update, add service providers to the `config/app.php`

	    'SleepingOwl\Seeder\SeederServiceProvider',

 3. That's all.

## Seeding

1. Import classes:
	
	```php
	use SleepingOwl\Seeder\DataSeeder;
	use SleepingOwl\Seeder\Seeder as SleepingOwlSeeder;
	```

2. Add seeding rule for table or model:
	
	```php
	SleepingOwlSeeder::table('table')->…
	# or
	SleepingOwlSeeder::model(\App\MyModel::class)->…
	```
	
3. Configure seeding rule (you can configure it globally, see details in "Global Configuration"):

	```php
	SleepingOwlSeeder::table('table')
		->truncate() # delete all data before seeding (default is off)
		->locale('de_DE') # locale for this seed (default is en_US)
		->times(100) # entities count to insert (default is 10)
		->ignoreExceptions() # ignore query exceptions (default is off)
		->...
	```
	
4. Configure seeding schema (see details in "Schema Configuration"):

	```php
	SleepingOwlSeeder::table('table')
		->…
		->seed(function (DataSeeder $schema)
		{
			$schema->title->unique()->firstName;
			$schema->country()->country;
			$schema->field('my_field')->optional(0.9)->phoneNumber;
		});
	```
	
5. Now you can use default command `php artisan db:seed` and new `seeder:lock`/`seeder:unlock` commands.

## Global Configuration

You can configure seeding settings globally:

```php
SleepingOwlSeeder::setDefaultLocale('de_DE');
SleepingOwlSeeder::setDefaultTimes(100);
SleepingOwlSeeder::truncateAll();
SleepingOwlSeeder::setDefaultIgnoreExceptions(true);
```

This configuration will be used as default for every seeder. Seeder configuration will override global configuration:

```php
SleepingOwlSeeder::setDefaultTimes(100);

SleepingOwlSeeder::table('table')
	->times(5) # this configuration has higher priority
	->...
```

## Schema Configuration

### Add Field to the Schema

You must provide schema for seeding. There is 3 ways to add field to the schema:

1. `$schema->field('my_field')`
2. `$schema->my_field`
3. `$schema->my_field()`

All these 3 ways will add field `my_field` to the schema.

### Provide Rules for Seeding the Field

You must use [fzaninotto/faker](https://github.com/fzaninotto/Faker) package rules:

```php
 # "phone" will be random phone number
 # "->phone" is field name
 # "->phoneNumber" is seeding rule
$schema->phone->phoneNumber;

 # "my_field" will be unique sentence with 6 words
$schema->my_field->unique()->sentence(6);

 # "country_title" will be country title in 90% cases and null in other 10%
$schema->country_title->optional(0.9)->country;

 # "post_id" will be id of any \App\Post entity
$schema->post_id->anyOf(\App\Post::class);

 # you can use your custom function for getting value for seeding
$schema->my_other_field->call(function ()
{
	return mt_rand(0, 10);
})

 # "int_field" will be random element from "$my_array"
$my_array = [1, 2, 3, 4];
$schema->int_field->randomElement($my_array);
```

## Lock/Unlock Table Seeding

### Locking

You can lock table seeding. This command will save table state and restores it with every seeding call.

You can lock one table:

```bash
php artisan seeder:lock table_name
```

Or all tables:

```bash
php artisan seeder:lock --all
```

### Unlocking

This command will delete table saved state and restores default behaviour.

```bash
php artisan seeder:unlock table_name
```

or

```bash
php artisan seeder:unlock --all
```

## Support Library

You can donate in BTC: 13k36pym383rEmsBSLyWfT3TxCQMN2Lekd

## Copyright and License

Package was written by Sleeping Owl for the Laravel framework and is released under the MIT License. See the LICENSE file for details.
