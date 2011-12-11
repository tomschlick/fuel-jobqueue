<?php

namepace Jobqueue;

class Model_Job_Driver_Mongodb extends \Mongo_Crud
{
	protected $collection = 'jobqueue';

	public function id()
	{
		return $this->_id;
	}
}