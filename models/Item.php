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

    public function __get($key)
    {
        if ('title' == $key) {
            $meta = array();
            $sql = "
                SELECT value.text
                FROM attribute,value
                WHERE value.item_id = ?
                AND value.attribute_id = attribute.id
                AND attribute.ascii_id = 'title'
                ";
            $sth = $this->db->prepare($sql);
            $sth->execute(array($this->id));
            $title = $sth->fetchColumn();
            if ($title) {
                return $title;
            } else {
                return $this->serial_number;
            }
        }
        return parent::__get($key);
    }

    public function getMetadata()
    {
        $meta = array();
        $sql = "
            SELECT attribute.ascii_id, value.text, value.id
            FROM attribute,value
            WHERE value.item_id = ?
            AND value.attribute_id = attribute.id
            ";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($this->id));
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $key = $row['ascii_id'];
            $val = $row['text'];
            $href = 'item/'.$this->serial_number.'/keyval/'.$row['id'];
            if (isset($meta[$key])) {
                $meta[$key][] = array('href' => $href, 'text' => $val);
            } else {
                $meta[$key] = array(array('href' => $href, 'text' => $val));
            }
        }
        $this->metadata = $meta;
        return $meta;
    }

    public function addMetadata($att_ascii,$value,$update=true)
    {
        if (!$att_ascii || !$value) {
            return;
        }
        $a = Attribute::findOrCreate($att_ascii);
        $val = new Value();
        $val->text = $value;
        $val->attribute_id = $a->id;
        $val->item_id = $this->id;
        if ($val->insert()) {
            if ($update) {
                $item->updated = date(DATE_ATOM);
                $item->update();
            }
        }
    }

    public function asJson($app_root='{APP_ROOT}')
    {
        $item_json = array();
        $item_json['id'] = $app_root.'/item/'.$this->serial_number;
        $item_json['app_root'] = $app_root;
        $item_json['created'] = $this->created;
        $item_json['updated'] = $this->updated;
        $links['self'] = '/item/'.$this->serial_number;
        $links['metadata'] = '/item/'.$this->serial_number.'/metadata';
        $item_json['links'] = $links;
        $item_json['metadata'] = $this->getMetadata();
        return json_encode($item_json);
    }

    public function update()
    {
        $meta = array();
        $sql = "
            SELECT attribute.search_column, value.text
            FROM attribute,value
            WHERE value.item_id = ?
            AND value.attribute_id = attribute.id
            ";
        $sth = $this->db->prepare($sql);
        $sth->execute(array($this->id));
        while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $key = $row['search_column'];
            $val = $row['text'];
            if (isset($meta[$key])) {
                $meta[$key][] = $val;
            } else {
                $meta[$key] = array($val);
            }
        }
        Search::findOrCreate($this->serial_number);
        $fields = array();
        $values = array();
        foreach( $meta as $sc => $vals) {
            $fields[]= $sc." = ?";
            $values[]= join(' ',$vals);
        }
        $set = join( ",", $fields );
        $sql = "UPDATE search SET $set WHERE rowid=?";
        $values[] = hexdec($this->serial_number);
        $sth = $this->db->prepare( $sql );
        if (!$sth->execute($values)) {
            $errs = $sth->errorInfo(); 
            if (isset($errs[2])) {
                throw new PDOException('could not update '.$errs[2]);
            }
        } else {
            $this->doc = $this->asJson();
            parent::update();
        }
    }

    public function expunge()
    {
        $sql = "DELETE FROM value WHERE item_id=?";
        $sth = $this->db->prepare( $sql );
        if (!$sth->execute(array($this->id))) {
            $errs = $sth->errorInfo(); 
            if (isset($errs[2])) {
                throw new PDOException('could not delete values'.$errs[2]);
            }
        } 
        $sql = "DELETE FROM search WHERE rowid=?";
        $id = hexdec($this->serial_number);
        $sth = $this->db->prepare( $sql );
        if (!$sth->execute(array($id))) {
            $errs = $sth->errorInfo(); 
            if (isset($errs[2])) {
                throw new PDOException('could not delete '.$errs[2]);
            }
        } else {
            return $this->delete();
        }
    }
}

