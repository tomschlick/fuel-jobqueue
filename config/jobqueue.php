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



return array(

	'driver'			=> 'mongodb',		// job storage driver
	'fork'				=> true,			// fork the jobs into separate processes to run more than one at a time
	'concurrent_forks'	=> 5,				// max number of concurrently running forks

	'types'				=> array(
		'beep'				=> 'Cli::beep',
		),

	);

/* End of file jobqueue.php */