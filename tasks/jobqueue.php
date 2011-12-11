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

class JobQueue
{
	public function __construct()
	{
		\Config::load('jobqueue');
	}

	public static function run()
	{
		$pending = \Job::get_pending();

		if($pending)
		{
			foreach($pending as $row)
			{
				if(\Config::get('fork'))
				{
					\Cli::spawn('php oil r jobqueue:process '.$row->id());
				}
			}
		}
	}

	public static function process($id = NULL)
	{
		$job = \Job::get($id);

		if(!$job)
		{
			return null;
		}

		$job->process();
	}
}