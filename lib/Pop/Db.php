<?php

class Pop_Db_Exception extends Exception {}

class Pop_Db
{

    public $db;
    protected $table;
    protected $order_by;
    protected $limit;
    protected $qualifiers;

    public function __construct() 
    { 
        $this->db = new PDO('sqlite:'.SQLITE_PATH);
    }

    public function load($id)
    {
        $table = $this->getTable();
        $sth = $this->db->prepare("SELECT * FROM $table WHERE id = ?");
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute(array($id));
        $row = $sth->fetch(PDO::FETCH_ASSOC);
        foreach ($row as $k => $v) {
            $this->$k = $v;
        }
        return $this;
    }

    function find()
    {
        $dbh = $this->db;
        $table = $this->getTable();
        $sets = array();
        $bind = array();
        $limit = '';
        foreach ($this->qualifiers as $qual) {
            $f = $qual['field'];
            $op = $qual['operator'];
            //allows is to add 'is null' qualifier
            if ('null' == $qual['value']) {
                $v = $qual['value'];
            } else {
                $v = $dbh->quote($qual['value']);
            }
            $sets[] = "$f $op $v";
        }
        $where = join( " AND ", $sets );
        if ($where) {
            $sql = "SELECT * FROM ".$table. " WHERE ".$where;
        } else {
            $sql = "SELECT * FROM ".$table;
        }
        if (isset($this->order_by)) {
            $sql .= " ORDER BY $this->order_by";
        }
        if (isset($this->limit)) {
            $sql .= " LIMIT $this->limit";
        }
        $sth = $dbh->prepare( $sql );
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }

        $sth->execute();
        $list = array();
        while ($obj = $sth->fetchObject(get_class($this)))
        {
            $list[] = $obj;
        }
        return $list;
    }


    function findOne()
    {
        $this->setLimit(1);
        $set = $this->find();
        if (count($set)) {
            foreach ($set[0] as $k => $v) {
                if (property_exists(get_class($this),$k)) {
                    $this->$k = $v;
                }
            }
            return $this;
        }
        return false;
    }

    public function addWhere($field,$value,$operator)
    {
        if ( 
            in_array(strtolower($operator),array('is not','is','ilike','like','not ilike','not like','=','!=','<','>','<=','>='))
        ) {
            $this->qualifiers[] = array(
                'field' => $field,
                'value' => $value,
                'operator' => $operator
            );
        } else {
            throw new Pop_Db_Exception('addWhere problem');
        }
    }

    function setLimit($limit)
    {
        $this->limit = $limit;
    }

    function orderBy($ob)
    {
        $this->order_by = $ob;
    }

    public function getHasOne($classname)
    {
        $related = new $classname();
        $related_table = $related->getTable();

        $sth = $this->db->prepare("SELECT * FROM $related_table WHERE id = ?");
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute(array($this->{$related_table.'_id'}));
        $one = $sth->fetchObject($classname);
        $this->$related_table = $one;
        return $one;
    }

    public function getHasMany($classname)
    {
        $list = array();
        $related = new $classname();
        $related_table = $related->getTable();
        $fk = $this->table.'_id';
        $sth = $this->db->prepare("SELECT * FROM $related_table WHERE $fk = ?");
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute(array($this->id));
        while ($obj = $sth->fetchObject($classname))
        {
            $list[] = $obj;
        }
        $plural = $related_table.'s';
        $this->$plural = $list;
        return $list;
    }

    public function listAll() 
    {
        $list = array();
        $table = $this->getTable();
        $sth = $this->db->prepare("SELECT * FROM $table");
        if (!$sth) {
            throw new PDOException('cannot create statement handle');
        }
        $sth->execute();
        while ($obj = $sth->fetchObject(get_class($this)))
        {
            $list[] = $obj;
        }
        return $list;
    }

    public function getTable()
    {
        if ($this->table) {
            return $this->table;
        } else {
            return strtolower(get_class($this));
        }
    } 
}

