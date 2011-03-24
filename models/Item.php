<?php

class Item extends Pop_Db
{
    public $id;
    public $serial_number;
    public $created;
    public $updated;

	public function __construct()
	{
        parent::__construct();
	}

}

