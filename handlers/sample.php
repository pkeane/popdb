<?php

class Pop_Handler_Sample extends Pop_Handler
{
	public $resource_map = array(
		'/' => 'sample',
		'{identifier}' => 'answer',
	);

	protected function setup($r)
	{
	}

	public function getSample($r) 
	{
		$t = new Pop_Template($r);
        $att = new Attribute();
        $a = $att->retrieve(1);
        $atts = array();
        foreach ($att->listAll() as $a) {
            $a->getHasMany('Value');
            $atts[] = $a;
        }
        $t->assign('atts',$atts);
		$r->renderResponse($t->fetch('sample.tpl'));
	}

	public function postToSample($r) 
	{
		$r->renderResponse($r->get('meta'));
	}

}

