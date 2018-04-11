<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2018/4/11 16:17
 */
require_once('mongoconnect.php');

$search = $_POST['search'] ?? "";
$page = $_POST['page'] ?? 1;
$tr_response = "";
$json = [];

$search_result = find('bookinfo', ['$or' => [['author' => ['$regex' => $search]], ['intro' => ['$regex' => $search]], ['title' => ['$regex' => $search]]]], ['limit' => 10, 'skip' => ($page - 1) * 10]);
foreach ($search_result as $book) {
    $tr_response .= <<<_HTML
                        <tr id="{$book['_id']}">
                            <td contenteditable="true">{$book['title']}</td>
                            <td contenteditable="true">{$book['catagory']}</td>
                            <td contenteditable="true">{$book['author']}</td>
                            <td contenteditable="true">{$book['intro']}</td>
                        </tr>
_HTML;
}

$book_num = count(find('bookinfo', ['$or' => [['author' => ['$regex' => $search,'$ne'=>'']], ['intro' => ['$regex' => $search,'$ne'=>'']], ['title' => ['$regex' => $search,'$ne'=>'']]]]));
$json['booknum'] = $book_num ?? "";
$json['tr_response'] = $tr_response ?? "";

print json_encode($json);
?>

