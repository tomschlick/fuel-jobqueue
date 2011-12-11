<?php

/**
 * Background Job Queue Processing for FuelPHP
 *
 * @package		Job Queue
 * @version		1.0
 * @author		Tom Schlick (tom@tomschlick.com)
 * @link		http://github.com/tomschlick/fuel-jobqueue
 *
 */

Autoloader::add_classes(array(
	'Job' 										=> __DIR__.'/classes/job.php',
	'JobTypeNotRegisteredException' 			=> __DIR__.'/classes/job.php',
	'Jobqueue\\Model_Job_Driver_Mongodb' 		=> __DIR__.'/classes/model/job/driver/mongodb.php',
));