<?php

class Jobqueue\JobTypeNotRegisteredException extends FuelException {}

class Job
{
	public function _init()
	{
		Config::load('jobqueue', true);
	}

	public static function queue($type = null, $data = null, $priority = 3, $bucket = null)
	{
		if(Config::get('jobqueue.types.'.$type, false) == false)
		{
			throw new Jobqueue\JobTypeNotRegisteredException($type);
		}

		$class = Inflector::classify('Model_Job_Driver_'.Config::get('jobqueue.driver'));

		$job_id = $class::forge(array(
			'type'		=> $type,
			'data'		=> serialize($data),
			'priority'	=> $priority,
			'bucket'	=> $bucket,
			))->save();

		return $job_id->id();
	}

	public function process()
}