<?php

class Pop_Handler_Item extends Pop_Handler
{
    public $resource_map = array(
        '{serial_number}' => 'item',
        '{serial_number}/metadata' => 'metadata',
    );

    protected function setup($r)
    {
    }

    public function getItem($r) 
    {
        $t = new Pop_Template($r);
        $atts = new Attribute();
        $atts->orderBy('ascii_id');
        $t->assign('attributes',$atts->find());
        $item = new Item();
        $item->serial_number = $r->get('serial_number');

        if ( $item->findOne() ) {
            $item->getMetadata();
            $t->assign('item',$item);
            $r->renderResponse($t->fetch('item.tpl'));
        } else {
            $r->renderRedirect('items');
        }
    }

    public function postToMetadata($r)
    {
        $item = new Item();
        $item->serial_number = $r->get('serial_number');
        if (!$r->get('ascii_id') || !$r->get('value')) {
            $r->renderError(417,'missing data');
        }
        if ( $item->findOne() ) {
            $a = Attribute::findOrCreate($r->get('ascii_id'));
            $val = new Value();
            $val->text = $r->get('value');
            $val->attribute_id = $a->id;
            $val->item_id = $item->id;
            if ($val->insert()) {

                $s = new Search();
                $scol = $a->search_column;
                $s->$scol = $val->text;
                $s->doc = $item->serial_number;
                $s->insert();

                $item->updated = date(DATE_ATOM);
                $item->update();
            }
            $r->renderRedirect('item/'.$item->serial_number);
        } else {
            $r->renderError(404);
        }

    }

    public function deleteItem($r) 
    {
        $item = new Item();
        $item->serial_number = $r->get('serial_number');
        if ( $item->findOne() ) {
            if ($item->getValuesCount()) {
                $r->renderError(409,'sorry this item has values');
            }
            if ($item->delete()) {
                $r->renderResponse('deleted item');
            } else {
                $r->renderError(500);
            }
        } else {
            $r->renderError(404);
        }
    }
}

