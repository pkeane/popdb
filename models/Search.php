<?php

class Search extends Pop_Db
{

	public function __construct()
	{
        parent::__construct();
	}

    public static function match($q,$col)
    {
        if (!$col) {
            $col = 'search';
        }
        $sql = "SELECT doc FROM search WHERE $col MATCH ?";
        $db = new PDO('sqlite:'.SQLITE_PATH);
        $sth = $db->prepare($sql);
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute(array($q));
        $list = array();
        while ($serial_number = $sth->fetchColumn()) {
            $item = Item::getBySerialNumber($serial_number);
            $list[] = $item;
        }
        return $list;
    }
}

