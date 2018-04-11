<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2018/3/14 17:46
 */

/*
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$filter = ['author'=>'杨绛'];
$options = [];
print "<br/>";

$query = new MongoDB\Driver\Query($filter,$options);
$rows = $manager -> executeQuery('bookinfo.bookinfo',$query);

print "<br/>";

foreach ($rows as $cursor) {
    print get_class_methods('stdClass Object');
    print_r($cursor);
    $a = get_object_vars($cursor);
    print $a['author'];
    print "<br/>";
}
*/
class MongoConn {
    protected function __construct(){
        $this->manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
    }

    protected function __clone() {
    }

    public static function startConn(){
        static $_test;
        if (empty($_test)){
            $_test = new MongoConn();
        }
        return $_test;
    }

}


function find ($collection,$filter=[],$options=[],$attr=''){
    $result = [];
    $conn = MongoConn::startConn();
    $query = new MongoDB\Driver\Query($filter,$options);
    $cursors = $conn->manager->executeQuery("bookinfo.$collection",$query);
    foreach ($cursors as $cursor) {
        if (! $attr) {
            $result[] = get_object_vars($cursor);
        }
        else {
            $result[] = get_object_vars($cursor)[$attr];
        }
    }
    return $result;
}


function insert($collection,$documents){
    $conn = MongoConn::startConn();
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($documents);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY,1000);
    $conn->manager->executeBulkWrite("bookinfo.$collection",$bulk,$writeConcern);
}

function delete($collection,$filter,$limit=0){
    $conn = MongoConn::startConn();
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete($filter,['limit'=>$limit]);
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY,1000);
    $conn->manager->executeBulkWrite("bookinfo.$collection",$bulk,$writeConcern);
}

function update($collection,$filter,$set,$upsert=false,$multi=false){
    $conn = MongoConn::startConn();
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        $filter,
        ['$set'=>$set],
        ['multi'=>$multi,'upsert'=>$upsert]
    );
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY,1000);
    $conn->manager->executeBulkWrite("bookinfo.$collection",$bulk,$writeConcern);
}

?>