<?php

class Pop_Handler_Items extends Pop_Handler
{
	public $resource_map = array(
		'/' => 'items',
		'generator' => 'generator',
	);

	protected function setup($r)
	{
	}

	public function getItems($r) 
	{
		$t = new Pop_Template($r);
        $item = new Item();
        $item->orderBy('updated DESC');
        $items = array();
        foreach ($item->find() as $it) {
            $it->getHasMany('Value');
            $items[] = $it;
        }
        $t->assign('items',$items);
		$r->renderResponse($t->fetch('items.tpl'));
	}

	public function postToGenerator($r) 
	{
        Item::generate();
        $r->renderRedirect('items');

	}

	public function postToItems($r) 
	{
		$content_type = $r->getContentType();
        print $content_type; exit;

	}
}

