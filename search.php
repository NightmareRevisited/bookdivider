<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2018/4/4 12:46
 */
require_once('mongoconnect.php');

if ("POST" == $_SERVER['REQUEST_METHOD']){

    $id = $_POST['id'];
    $admin = $_POST['admin'];
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";
    $identity = $_POST['identity'] ?? "";
    $type = $_POST['type'];
    $content = $_POST['content'] ?? "";
    $time=date("Y-m-d H:i:s");

    $action = explode("_",$type)[1];
    $object = explode("_",$type)[0];
    if ('search' != $action and 'insert' != $action) {
        insert('record', ['action' => $action,'type'=>$object,'object'=>$username,'admin'=>$admin, "time" => "{$time}"]);
    }

    if ("user_delete" == $type){
        delete('user',['_id'=>new MongoDB\BSON\ObjectID($id)]);
        $result = find('user',['username'=>['$regex'=>$content,'$ne'=>'']]);
        foreach ($result as $user) {
            $other_identity = ['user','admin'][1 - array_search($user['indentity'], ['user','admin'])];
                print<<<_HTML
                        <tr id="{$user['_id']}">
                            <td contenteditable="true">{$user['username']}</td >
                            <td contenteditable="true">{$user['password']}</td >
                            <td contenteditable = "true" >
                                <select id="select{$user['_id']}">
                                    <option >{$user['indentity']}</option >
                                    <option >{$other_identity}</option >
                                </select ></td >
                            <td >
                                <button class="fa fa-window-close" onclick="user_operation('user_delete','{$user['_id']}')"> Delete</button >
                                <button class="fa fa-save" onclick="user_operation('user_update','{$user['_id']}')"> Save</button >
                                <div id="response{$user['_id']}"></div>
                            </td >
                        </tr >
_HTML;

        }
    }
    elseif ("user_update" == $type){
        update('user',['_id'=>new MongoDB\BSON\ObjectID($id)],['username'=>$username,'password'=>$password,'indentity'=>$identity]);
        print count(find('user',['username'=>['$ne'=>'']]));
    }
    elseif ('user_search' == $type){
        $result = find('user',['username'=>['$regex'=>$content,'$ne'=>'']]);
        foreach ($result as $user) {
            $other_identity = ['user','admin'][1 - array_search($user['indentity'], ['user','admin'])];
                print<<<_HTML
                        <tr id="{$user['_id']}">
                            <td contenteditable="true">{$user['username']}</td >
                            <td contenteditable="true">{$user['password']}</td >
                            <td contenteditable = "true" >
                                <select id="select{$user['_id']}">
                                    <option >{$user['indentity']}</option >
                                    <option >{$other_identity}</option >
                                </select ></td >
                            <td >
                                <button class="fa fa-window-close" onclick="user_operation('user_delete','{$user['_id']}')"> Delete</button >
                                <button class="fa fa-save" onclick="user_operation('user_update','{$user['_id']}')"> Save</button >
                                <div id="response{$user['_id']}"></div>
                            </td >
                        </tr >
_HTML;

        }
    }
    elseif ("user_insert" == $type){
        update('user',['username'=>''],['username'=>'','password'=>'','indentity'=>'user'],true);
        foreach (find('user',['username'=>'']) as $user){
            $other_identity = ['user','admin'][1 - array_search($user['indentity'], ['user','admin'])];
            print<<<_HTML
                        <tr id="{$user['_id']}">
                            <td contenteditable="true">{$user['username']}</td >
                            <td contenteditable="true">{$user['password']}</td >
                            <td contenteditable = "true" >
                                <select id="select{$user['_id']}">
                                    <option >{$user['indentity']}</option >
                                    <option >{$other_identity}</option >
                                </select ></td >
                            <td >
                                <button class="fa fa-save" onclick="user_operation('user_update','{$user['_id']}')"> Save</button >
                                <div id="response{$user['_id']}"></div>
                            </td >
                        </tr >
_HTML;
        }
        foreach (find('user',['username'=>['$regex'=>$content,'$ne'=>'']]) as $user){
            $other_identity = ['user','admin'][1 - array_search($user['indentity'], ['user','admin'])];
                print<<<_HTML
                        <tr id="{$user['_id']}">
                            <td contenteditable="true">{$user['username']}</td >
                            <td contenteditable="true">{$user['password']}</td >
                            <td contenteditable = "true" >
                                <select id="select{$user['_id']}">
                                    <option >{$user['indentity']}</option >
                                    <option >{$other_identity}</option >
                                </select >
                            </td >
                            <td >
                                <button class="fa fa-window-close" onclick="user_operation('user_delete','{$user['_id']}')"> Delete</button >
                                <button class="fa fa-save" onclick="user_operation('user_update','{$user['_id']}')"> Save</button >
                                <div id="response{$user['_id']}"></div>
                            </td >
                        </tr >
_HTML;

        }
    }
}