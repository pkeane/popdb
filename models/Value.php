<?php

class Value extends Pop_Db
{
    public $id;
    public $attribute_id;
    public $item_id;
    public $text;

	public function __construct()
	{
        parent::__construct();
	}

}

