<?php

class Value extends Pop_Db
{

    public $attribute;
    public $item;

	public function __construct()
	{
        parent::__construct();
	}

    public function getAttribute()
    {
        $att = new Attribute();
        $att->load($this->attribute_id);
        $this->attribute = $att;
    }
}

