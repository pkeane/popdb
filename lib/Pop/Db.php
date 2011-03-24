<?php

class Pop_Db
{

    public $db;
    protected $table;

    public function __construct() 
    { 
        $this->db = new PDO('sqlite:'.SQLITE_PATH);
    }

    public function retrieve($id)
    {
        $table = $this->getTable();
        $sth = $this->db->prepare("SELECT * FROM $table WHERE id = ?");
		if (!$sth) {
			throw new PDOException('cannot create statement handle');
		}
        $sth->execute(array($id));
        return $sth->fetchObject(get_class($this));
    }

    public function retrieveWhere($field,$value,$operator)
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
			throw new Dase_DBO_Exception('addWhere problem');
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

