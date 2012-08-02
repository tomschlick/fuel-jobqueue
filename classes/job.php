<?php

class JobTypeNotRegisteredException extends FuelException {}

class Job
{
	public static function _init()
	{
		Config::load('jobqueue', true);
	}

	public static function queue($type = null, $data = null, $priority = 3, $bucket = null)
	{
		if(Config::get('jobqueue.types.'.$type, false) == false)
		{
			throw new JobTypeNotRegisteredException($type);
		}

		$class = self::classname();

		$job_id = $class::forge(array(
			'type'		=> $type,
			'data'		=> serialize($data),
			'priority'	=> $priority,
			'bucket'	=> $bucket,
			'started'	=> false,
			'error'		=> false,
			))->save();

		return $job_id->id();
	}

	public static function count()
	{
		$mongo = Mongo_Db::instance();
		return $mongo->where(array('started' => true, 'error' => false))->count(Config::get('jobqueue.collection_name'));
	}

	public static function total()
	{
		$mongo = Mongo_Db::instance();
		return $mongo->count(Config::get('jobqueue.collection_name'));
	}

	public static function get($id = null)
	{
		$class = self::classname();
		$queue = $class::find_by_pk($id);

		return $queue;
	}

	public static function get_queue()
	{
		$class = self::classname();
		$queue = $class::find(array(
			'where'		=> array(
				'started'		=> false,
				'error'			=> false,
				),
			'order_by'	=> array('priority' => 'desc', '_id' => 'asc')
			));

		return $queue;
	}

	public static function process($job = null)
	{
		$method = Config::get('jobqueue.types.'.$job['type']);
		if(!is_callable($method))
		{
			\Cli::write('Method "'.$method.'" is not callable for job # '.$job->_id);
			return false;
		}
		\Cli::write('Calling method "'.$method.'" for job # '.$job->_id);
		call_user_func($method, unserialize($job['data']));
		return true;
	}

	protected static function classname()
	{
		return Inflector::classify('Jobqueue\\Model_Job_Driver_'.Config::get('jobqueue.driver'));
	}
}