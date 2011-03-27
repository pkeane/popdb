<?php

class Attribute extends Pop_Db
{
    public $table = 'attribute';
    public $id;
    public $values = array();

	public function __construct() 
	{
        parent::__construct();
	}

    public static function findOrCreate($ascii_id) {
        $ascii_id = Pop_Util::dirify($ascii_id);
        $a = new Attribute();
        $a->ascii_id = $ascii_id;
        if ($a->findOne()) {
            return $a;
        } else {
            $id = $a->insert();
            $a->search_column = 'c'.$id;
            $a->update();
            return $a;
        }
    }
}

