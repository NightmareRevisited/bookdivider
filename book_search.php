<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2018/4/8 20:17
 */

require_once('mongoconnect.php');
require_once('unicode.php');

function book_divide($intro,$author){
    $author = str2unicode($author);
    $intro = str2unicode($intro);
    exec("C:/Python27/python.exe E:\pyworkspace\grad_design\gd\book_divide.py {$intro} {$author}",$output);
    return $output;
}

if ("POST" == $_SERVER['REQUEST_METHOD']) {
    $json = [];
    $search = $_POST['search'];
    $page = (int)($_POST['page']);
    $type = $_POST['type'] ?? "";
    $id = $_POST['id'] ?? "";
    $title = $_POST['title'] ?? "";
    $author = $_POST['author'] ?? "";
    $category = $_POST['category'] ?? "";
    $intro = $_POST['intro'] ?? "";
    $tr_response = "";
    $admin = $_POST['admin'] ?? "";

    if ($admin) {
        $action = explode("_", $type)[1];
        $object = explode("_", $type)[0];
        $time = date("Y-m-d H:i:s");
        if ('search' != $action && 'insert' != $action) {
            insert('record', ['action' => $action, 'type' => $object, 'object' => $title, 'admin' => $admin, "time" => "{$time}"]);
        }
    }

    if ("book_insert" == $type){
        update('bookinfo',['catagory'=>""],['textrank_tags'=>'','author'=>'','title'=>'','intro'=>''],true);
        foreach (find('bookinfo',['catagory'=>'']) as $book){
            $tr_response .= <<<_HTML
            <tr id="{$book['_id']}">
                            <td contenteditable="true">{$book['title']}</td>
                            <td contenteditable="true">{$book['catagory']}</td>
                            <td contenteditable="true">{$book['author']}</td>
                            <td contenteditable="true">{$book['intro']}</td>
                            <td>
                                <button class="fa fa-window-close" onclick="book_operation('book_delete','{$book['_id']}')">Delete</button>
                                <button class="fa fa-save" onclick="book_operation('book_update','{$book['_id']}')">Save</button>
                                <div id="{$book['_id']}status"></div>
                            </td>
                        </tr>
_HTML;
        }
        foreach (find('bookinfo', ['$or' => [['author' => ['$regex' => $search]], ['intro' => ['$regex' => $search]], ['title' => ['$regex' => $search]]]], ['limit' => 10, 'skip' => ($page - 1) * 10]) as $book){
            $tr_response .= <<<HTML
            <tr id="{$book['_id']}">
                            <td contenteditable="true">{$book['title']}</td>
                            <td contenteditable="true">{$book['catagory']}</td>
                            <td contenteditable="true">{$book['author']}</td>
                            <td contenteditable="true">{$book['intro']}</td>
                            <td>
                                <button class="fa fa-window-close" onclick="book_operation('book_delete','{$book['_id']}')">Delete</button>
                                <button class="fa fa-save" onclick="book_operation('book_update','{$book['_id']}')">Save</button>
                                <div id="{$book['_id']}status"></div>
                            </td>
                        </tr>
HTML;
        }
    }
    elseif ("book_update" == $type){
        if ($category) {
            update('bookinfo', ['_id' => new MongoDB\Bson\ObjectID($id)], ['title' => $title, 'intro' => $intro, 'author' => $author, 'catagory' => $category]);
        }
        else {
            $NB_result = book_divide($intro,$author);
            $trained_category = end($NB_result);
            array_pop($NB_result);
            if ($trained_category) {
                update('bookinfo', ['_id' => new MongoDB\Bson\ObjectID($id)], ['title' => $title, 'intro' => $intro, 'author' => $author, 'catagory' => $trained_category,'textrank_tags'=>$NB_result]);
            }
        }
    }
    elseif ('book_delete' == $type){
        delete('bookinfo',['_id'=>new MongoDB\BSON\ObjectID($id)]);
        foreach (find('bookinfo', ['$or' => [['author' => ['$regex' => $search]], ['intro' => ['$regex' => $search]], ['title' => ['$regex' => $search]]]], ['limit' => 10, 'skip' => ($page - 1) * 10]) as $book) {
            $tr_response .= <<<HTML
            <tr id="{$book['_id']}">
                            <td contenteditable="true">{$book['title']}</td>
                            <td contenteditable="true">{$book['catagory']}</td>
                            <td contenteditable="true">{$book['author']}</td>
                            <td contenteditable="true">{$book['intro']}</td>
                            <td>
                                <button class="fa fa-window-close" onclick="book_operation('book_delete','{$book['_id']}')">Delete</button>
                                <button class="fa fa-save" onclick="book_operation('book_update','{$book['_id']}')">Save</button>
                                <div id="{$book['_id']}status"></div>
                            </td>
                        </tr>
HTML;
        }
    }
    else {
        $search_result = find('bookinfo', ['$or' => [['author' => ['$regex' => $search]], ['intro' => ['$regex' => $search]], ['title' => ['$regex' => $search]]]], ['limit' => 10, 'skip' => ($page - 1) * 10]);
        foreach ($search_result as $book) {
            $tr_response .= <<<_HTML
                        <tr id="{$book['_id']}">
                            <td contenteditable="true">{$book['title']}</td>
                            <td contenteditable="true">{$book['catagory']}</td>
                            <td contenteditable="true">{$book['author']}</td>
                            <td contenteditable="true">{$book['intro']}</td>
                            <td>
                                <button class="fa fa-window-close" onclick="book_operation('book_delete','{$book['_id']}')">Delete</button>
                                <button class="fa fa-save" onclick="book_operation('book_update','{$book['_id']}')">Save</button>
                                <div id="{$book['_id']}status"></div>
                            </td>
                        </tr>
_HTML;
        }
    }
    $book_num = count(find('bookinfo', ['$or' => [['author' => ['$regex' => $search,'$ne'=>'']], ['intro' => ['$regex' => $search,'$ne'=>'']], ['title' => ['$regex' => $search,'$ne'=>'']]]]));
    $json['category'] = $trained_category ?? $category;
    $json['booknum'] = $book_num ?? "";
    $json['tr_response'] = $tr_response ?? "";
    print json_encode($json);
}
?>