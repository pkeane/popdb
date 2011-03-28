<?php

class Attribute extends Pop_Db
{
    public $values = array();

	public function __construct() 
	{
        parent::__construct();
	}

    public static function findOrCreate($ascii_id) {
        if (!$ascii_id) { $ascii_id = '-'; }
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

    public function getValuesCount()
    {
        $values = $this->getHasMany('Value');
        return count($values);
    }
}

