<?php

class Pop_Handler_Attribute extends Pop_Handler
{
    public $resource_map = array(
        '{ascii_id}' => 'attribute',
    );

    protected function setup($r)
    {
    }

    public function getAttribute($r) 
    {
        $t = new Pop_Template($r);
        $att = new Attribute();
        $att->ascii_id = $r->get('ascii_id');

        if ( $att->findOne() ) {
            $t->assign('att',$att);
            $r->renderResponse($t->fetch('attribute.tpl'));
        } else {
            $r->renderRedirect('attributes');
        }
    }

    public function getAttributeJson($r) 
    {
        $t = new Pop_Template($r);
        $att = new Attribute();
        $att->ascii_id = $r->get('ascii_id');

        if ( $att->findOne() ) {
            $r->renderResponse(json_encode($att->asArray()));
        } else {
            $r->renderError(404);
        }
    }

    public function deleteAttribute($r) 
    {
        $att = new Attribute();
        $att->ascii_id = $r->get('ascii_id');
        if ( $att->findOne() ) {
            if ($att->getValuesCount()) {
                $r->renderError(409,'sorry this attribute has values');
            }
            if ($att->delete()) {
                $r->renderResponse('deleted attribute');
            } else {
                $r->renderError(500);
            }
        } else {
            $r->renderError(404);
        }
    }
}

