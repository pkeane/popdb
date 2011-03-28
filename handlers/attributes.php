<?php

class Pop_Handler_Attributes extends Pop_Handler
{
	public $resource_map = array(
		'/' => 'attributes',
		'{identifier}' => 'answer',
	);

	protected function setup($r)
	{
	}

	public function getAttributes($r) 
	{
		$t = new Pop_Template($r);
        $att = new Attribute();
        $atts = array();
        foreach ($att->find() as $a) {
            $a->getHasMany('Value');
            $atts[] = $a;
        }
        $t->assign('atts',$atts);
		$r->renderResponse($t->fetch('attributes.tpl'));
	}

	public function postToAttributes($r) 
	{
		$att = $r->get('att');
        $a = Attribute::findOrCreate($att);
        $r->renderRedirect('attributes');

	}
}

