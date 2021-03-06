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
    $db->exec("CREATE TABLE item (id INTEGER PRIMARY KEY, serial_number TEXT, created TEXT, updated TEXT, doc TEXT)");    
    $db->exec("CREATE INDEX idx_serial_number ON item (serial_number)");

    $db->exec("CREATE TABLE attribute (id INTEGER PRIMARY KEY, ascii_id TEXT, search_column TEXT, created TEXT)");    
    $db->exec("CREATE INDEX idx_ascii_id ON attribute (ascii_id)");

    $db->exec("CREATE TABLE value (id INTEGER PRIMARY KEY, attribute_id INTEGER, item_id INTEGER, text TEXT)");    
    $db->exec("CREATE INDEX idx_item_id ON value (item_id)");
    $db->exec("CREATE INDEX idx_attribute_id ON value (attribute_id)");

    $ts = date(DATE_ATOM);
    $db->exec("INSERT INTO attribute (ascii_id,search_column,created) VALUES ('title','c1','$ts')");

    //create 500 columns in search table
    $cols = array();
    foreach (range(0,499) as $r) {
        $cols[] = 'c'.$r;
    }
    $cols[] = 'serial_number';
    $cstr = join(',',$cols);

    $db->exec("CREATE VIRTUAL TABLE search USING fts3($cstr)");    
    $db = NULL;
    echo "<html><body>database has been created <a href=\".\">continue</a></body></html>";
} catch(PDOException $e) {
    print 'Exception : '.$e->getMessage();
}

