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
        $r->checkCache(33);
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
        Item::generate();
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

