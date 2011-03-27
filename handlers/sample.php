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
        $atts = array();
        foreach ($att->find() as $a) {
            $a->getHasMany('Value');
            $atts[] = $a;
        }
        $t->assign('atts',$atts);
		$r->renderResponse($t->fetch('sample.tpl'));
	}

	public function postToSample($r) 
	{
		$meta = $r->get('meta');
        $parts = explode(':',$meta);
        if (count($parts) > 1) {
            $att = $parts[0];
            $val = $parts[1];
        } else {
            $att = $meta;
        }
        $a = Attribute::findOrCreate($att);
        $r->renderRedirect('sample');

	}
}

