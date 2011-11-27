<?php

class Pop_Handler_Search extends Pop_Handler
{
	public $resource_map = array(
		'/' => 'search',
	);

	protected function setup($r)
	{
	}

	public function getSearch($r) 
	{
		$t = new Pop_Template($r);
        $q = trim($r->get('q'));
        $col = '';
        $ascii_id = '';
        $parts = explode(':',$q);
        if (count($parts) > 1) {
            $ascii_id = trim(array_shift($parts));
            $q = trim(join(':',$parts));
            $a = new Attribute();
            $a->ascii_id = $ascii_id;
            if ($a->findOne()) {
                $col = $a->search_column;
            }
        } 
        $t->assign('ascii_id',$ascii_id);
        $t->assign('q',$q);
        $serial_numbers = Search::match($q,$col);
        $items = array();
        foreach ($serial_numbers as $sn) {
            $items[] = Item::getBySerialNumber($sn);
        }
        $t->assign('items',$items);
		$r->renderResponse($t->fetch('search.tpl'));
	}
}

