<?php

class Attribute extends Pop_Db
{
    public $table = 'attribute';
    public $id;
    public $ascii_id;
    public $created;
    public $search_column;
    public $values = array();

	public function __construct()
	{
        parent::__construct();
	}

}

