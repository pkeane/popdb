<?php

class Pop_Handler_Item extends Pop_Handler
{
    public $resource_map = array(
        '{serial_number}' => 'item',
        '{serial_number}/metadata' => 'metadata',
        '{serial_number}/keyval/{id}' => 'keyval',
    );

    protected function setup($r)
    {
    }

    public function postToKeyval($r) 
    {
        $item = new Item();
        $item->serial_number = $r->get('serial_number');
        if ( $item->findOne() ) {
            $kv = new Value();
            if (!$kv->load($r->get('id'))) {
                $r->renderError(404,'no such keyval');
            }
            $kv->text = $r->get('text');
            $kv->update();
            $item->updated = date(DATE_ATOM);
            $item->update();
            $r->renderRedirect('item/'.$item->serial_number);
        } else {
            $r->renderError(404,'no such item');
        }
    }

    public function getKeyval($r) 
    {
        $t = new Pop_Template($r);
        $atts = new Attribute();
        $atts->orderBy('ascii_id');
        $t->assign('attributes',$atts->find());
        $item = new Item();
        $item->serial_number = $r->get('serial_number');

        if ( $item->findOne() ) {
            $kv = new Value();
            if (!$kv->load($r->get('id'))) {
                $r->renderError(404,'no such keyval');
            }
            $kv->getAttribute();
            $t->assign('kv',$kv);
            $t->assign('item',$item);
            $r->renderResponse($t->fetch('keyval.tpl'));
        } else {
            $r->renderError(404,'no such item');
        }
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
            $item->update();
            $t->assign('item',$item);
            $data = json_decode($item->doc,1);
            $metadata = $data['metadata'];
            $t->assign('metadata',$metadata);
            $r->renderResponse($t->fetch('item.tpl'));
        } else {
            $r->renderRedirect('items');
        }
    }

    public function getItemJson($r) 
    {
        $item = new Item();
        $item->serial_number = $r->get('serial_number');
        if ( $item->findOne() ) {
            //$item_json = str_replace('{APP_ROOT}',$r->app_root,$item->doc);
            $item_json = $item->asJson($r->app_root);
            $r->renderResponse($item_json);
        } else {
            $r->renderError(404);
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
            if ($item->expunge()) {
                $r->renderResponse('deleted item');
            } else {
                $r->renderError(500);
            }
        } else {
            $r->renderError(404);
        }
    }
}

