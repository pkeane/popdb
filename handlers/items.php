<?php

class Pop_Handler_Items extends Pop_Handler
{
    public $resource_map = array(
        '/' => 'items',
        'new' => 'new_item_form',
        'generator' => 'generator',
    );

    protected function setup($r)
    {
    }

    public function getNewItemForm($r) 
    {
        $t = new Pop_Template($r);
        $r->renderResponse($t->fetch('new_item_form.tpl'));
    }

    public function getItemsJson($r) 
    {
        $items = new Item();
        $items->orderBy('updated DESC');
        //$items->setLimit(10);
        $set = array();
        foreach ($items->find() as $item) {
            $item = clone($item);
            $set[] = array(
                'serial_number' => $item->serial_number,
                'title' => $item->title, 
                'updated' => $item->updated,
            );
        }
        $r->renderResponse(json_encode($set));
    }

    public function getItems($r) 
    {
        //    $r->checkCache(33);
        $t = new Pop_Template($r);
        $items = new Item();
        $items->setColumns(array('id','serial_number'));
        $items->orderBy('updated DESC');
        //$items->setLimit(10);
        $t->assign('items',$items->find());
        $r->renderResponse($t->fetch('items.tpl'));
    }

    public function postToGenerator($r) 
    {
        $item = Item::generate();

        if ($r->get('note')) {
            $a = Attribute::findOrCreate('note');
            $val = new Value();
            $val->text = $r->get('note');
            $val->attribute_id = $a->id;
            $val->item_id = $item->id;
            if ($val->insert()) {
                $item->updated = date(DATE_ATOM);
                $item->update();
            }
        }
        if ($r->get('title')) {
            $a = Attribute::findOrCreate('title');
            $val = new Value();
            $val->text = $r->get('title');
            $val->attribute_id = $a->id;
            $val->item_id = $item->id;
            if ($val->insert()) {
                $item->updated = date(DATE_ATOM);
                $item->update();
            }
        }
        $r->renderRedirect('items');

    }

    public function postToItems($r) 
    {
        $content_type = $r->getContentType();
        if ('application/json' == $content_type) {
            $item = Item::generate();
            $body = $r->getBody();
            $data = json_decode($body,1);
            $metadata_array = $data['metadata'];
            foreach ($metadata_array as $k => $v_ar) {
                foreach ($v_ar as $v) {
                    $item->addMetadata($k,$v,false);
                }
            }
            $item->update();
            $item_doc = str_replace('{APP_ROOT}',$r->app_root,$item->doc);
            header("HTTP/1.1 201 Created");
            header("Content-Type: application/json");
            header("Location: ".$r->app_root."/item/".$item->serial_number);
            echo $item_doc;
            exit;
        }
    }
}

