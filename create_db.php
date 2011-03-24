<?php
  try
  {
    //open the database
    $db = new PDO('sqlite:data/db.sqlite');

    //create the table 
    $db->exec("CREATE TABLE item (id INTEGER PRIMARY KEY, serial_number TEXT, created TEXT, updated TEXT)");    
    $db->exec("CREATE TABLE attribute (id INTEGER PRIMARY KEY, ascii_id TEXT, search_column TEXT, created TEXT)");    
    $db->exec("CREATE TABLE value (id INTEGER PRIMARY KEY, attribute_id INTEGER, item_id INTEGER, text TEXT)");    

    $ts = date(DATE_ATOM);
    $db->exec("INSERT INTO attribute (ascii_id,created) VALUES ('title','$ts')");
    $db->exec("INSERT INTO attribute (ascii_id,created) VALUES ('description','$ts')");
    $db->exec("INSERT INTO attribute (ascii_id,created) VALUES ('keyword','$ts')");

    $db->exec("INSERT INTO item (serial_number,created,update) VALUES ('abc','$ts','$ts')");
    $db->exec("INSERT INTO item (serial_number,created,update) VALUES ('def','$ts','$ts')");
    $db->exec("INSERT INTO item (serial_number,created,update) VALUES ('ghi','$ts','$ts')");

    $db->exec("INSERT INTO value (attribute_id,item_id,text) VALUES (1,1,'my title')");
    $db->exec("INSERT INTO value (attribute_id,item_id,text) VALUES (1,2,'my second title')");
    $db->exec("INSERT INTO value (attribute_id,item_id,text) VALUES (1,3,'my third title')");

    //create 500 columns in search table
    $cols = array();
    foreach (range(0,499) as $r) {
        $cols[] = 'c'.$r;
    }
    $cols[] = 'doc';
    $cstr = join(',',$cols);

    $db->exec("CREATE VIRTUAL TABLE search USING fts3($cstr)");    
    $db->exec("INSERT INTO search (doc,c333) VALUES (3,'once upon a time')");

    $result = $db->query("SELECT doc FROM search WHERE search MATCH 'c333:upon'");

    foreach($result as $row)
    {
      print "DOC ".$row['doc']."\n";
    }

    $result = $db->query("SELECT * FROM attribute");

    foreach($result as $row)
    {
      print "att ".$row['ascii_id']."\n";
    }


    // close the database connection
    $db = NULL;
  }
  catch(PDOException $e)
  {
    print 'Exception : '.$e->getMessage();
  }
?>

