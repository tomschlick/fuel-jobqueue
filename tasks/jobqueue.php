<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2011 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;

/**
 * Job Queue task
 *
 *
 * @package		Job Queue
 * @version		1.0
 * @author		Tom Schlick
 */

class Jobqueue
{
	public function _construct()
	{
		\Config::load('jobqueue', true);
	}

	public static function run()
	{
		$pending = \Job::get_queue();

		if($pending)
		{
			foreach($pending as $row)
			{
				if(\Config::get('jobqueue.fork'))
				{
					$count = \Job::count();

					while($count >= \Config::get('jobqueue.concurrent_forks'))
					{
						sleep(2);
						\Cli::write('too many jobs, sleeping...');
						$count = \Job::count();
					}

					\Cli::spawn('php oil r jobqueue:process '.$row->_id);
				}
				else
				{
					self::process($row->_id);
				}
			}
		}
	}

	public static function process($id = NULL)
	{
		ini_set('memory_limit', '2048M');
		$job = \Job::get($id);
		\Cli::write('Starting Job # '.$id);

		if(!$job or !empty($job->started))
		{
			return null;
		}

		$job->started = true;
		$job->save();
		if( ! $out = \Job::process($job))
		{
			\Cli::write('An error occurred with job # '.$id);
			$job->error = true;
			$job->save();
		}
		\Cli::write('Removing Job # '.$id);
		$job->delete();
	}
}