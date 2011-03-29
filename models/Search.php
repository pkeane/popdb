<?php

class Search
{
    public $rowid;
    public $serial_number;

	public function __construct()
	{
        $this->db = new PDO('sqlite:'.SQLITE_PATH);
	}

    public static function findOrCreate($serial_number) {
        $rowid = hexdec($serial_number);
        $sql = "SELECT serial_number FROM search WHERE rowid = ?";
        $db = new PDO('sqlite:'.SQLITE_PATH);
        $sth = $db->prepare($sql);
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute(array($rowid));
        $row = $sth->fetch();
        if ($row) {
            $s = new Search();
            $s->rowid = $rowid;
            $s->serial_number = $row['serial_number'];
            return $s;
        } else {
            $s = new Search();
            $s->rowid = $rowid;
            $sql = "INSERT INTO search (docid,serial_number) VALUES (?,?)";
            $db = new PDO('sqlite:'.SQLITE_PATH);
            $sth = $db->prepare($sql);
            if (!$sth) {
                throw new PDOException('cannot create statement handle');
            }
            $sth->execute(array($rowid,$serial_number));
            return $s;
        }
    }

    public static function match($q,$col)
    {
        if (!$col) {
            $col = 'search';
        }
        $sql = "SELECT serial_number FROM search WHERE $col MATCH ?";
        $db = new PDO('sqlite:'.SQLITE_PATH);
        $sth = $db->prepare($sql);
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute(array($q));
        $list = array();
        while ($serial_number = $sth->fetchColumn()) {
            $list[] = $serial_number;
        }
        return $list;
    }
}

