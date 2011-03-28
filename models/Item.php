<?php

class Item extends Pop_Db
{
    public $values = array();
    public $metadata = array();

	public function __construct()
	{
        parent::__construct();
	}

    public static function getUniqueSerialNumber($serial_number=null) 
    {
        if (!$serial_number) {
            $serial_number = dechex(mt_rand(1048576,16777215));
            return Item::getUniqueSerialNumber($serial_number);
        }
        $item = new Item();
        $item->serial_number = $serial_number;
        if ($item->findOne()) {
            return Item::getUniqueSerialNumber($serial_number);
        } else {
            return $serial_number;
        }
    }

    public static function getBySerialNumber($sn)
    {
        $item = new Item();
        $item->serial_number = $sn;
        if ($item->findOne()) {
            return $item;
        } else {
            return false;
        }
    }

    public static function generate()
    {
        $item = new Item();
        $item->serial_number = Item::getUniqueSerialNumber();
        $item->created = date(DATE_ATOM);
        $item->updated = date(DATE_ATOM);
        $item->insert();
        return $item;
    }

    public function getValuesCount()
    {
        $values = $this->getHasMany('Value');
        return count($values);
    }

    public function getMetadata()
    {
        $meta = array();
        $sql = "
            SELECT attribute.ascii_id, value.text
            FROM attribute,value
            WHERE value.item_id = ?
            AND value.attribute_id = attribute.id
            ";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($this->id));
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $key = $row['ascii_id'];
            $val = $row['text'];
            if (isset($meta[$key])) {
                $meta[$key][] = $val;
            } else {
                $meta[$key] = array($val);
            }
        }
        $this->metadata = $meta;
        return $meta;
    }
}

