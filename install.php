<?php
try {
    $data_dir = dirname(__FILE__).'/data';
    $db_file = $data_dir.'/db.sqlite';

    if (!is_writable($data_dir)) {
        echo "$data_dir is not writable.";
        exit;
    } else {
        if (!file_exists($db_file)) {
            touch($db_file);
        } else {
            echo "$db_file already exists";
            exit;
        }
    }
    //open the database
    $db = new PDO('sqlite:'.$db_file);

    //create the table 
    $db->exec("CREATE TABLE item (id INTEGER PRIMARY KEY, serial_number TEXT, created TEXT, updated TEXT)");    
    $db->exec("CREATE TABLE attribute (id INTEGER PRIMARY KEY, ascii_id TEXT, search_column TEXT, created TEXT)");    
    $db->exec("CREATE TABLE value (id INTEGER PRIMARY KEY, attribute_id INTEGER, item_id INTEGER, text TEXT)");    

    $ts = date(DATE_ATOM);
    $db->exec("INSERT INTO attribute (ascii_id,search_column,created) VALUES ('title','c1','$ts')");

    //create 500 columns in search table
    $cols = array();
    foreach (range(0,499) as $r) {
        $cols[] = 'c'.$r;
    }
    $cols[] = 'doc';
    $cstr = join(',',$cols);

    $db->exec("CREATE VIRTUAL TABLE search USING fts3($cstr)");    
    $db = NULL;
    echo "database has been created";
} catch(PDOException $e) {
    print 'Exception : '.$e->getMessage();
}

