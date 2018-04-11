<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2018/3/27 12:14
 */

require_once('mongoconnect.php');

if ("POST" == $_SERVER['REQUEST_METHOD']){
    $status = $_POST['status'] ?? "";
    $username = $_POST['username'] ?? "";
    $username_ = htmlentities($username);
    $password = $_POST['password'] ?? "";
    $json = [
        'usernameResponse'=>"",
        'passwordResponse'=>"",
        'method'=>""
    ];
    if ("login"== $status){
        $login_result = find ('user',['username'=>$username,'password'=>$password]);
        if (!$login_result){
            $json['passwordResponse'] = "<font color='red'>Mistakes happened in username or password.</font>";
        }
        else{
            $json['passwordResponse'] = "<font color='#7cfc00'>Success</font>";
            $json['method'] = $login_result[0]['indentity'];
        }
    }
    elseif ("signup" == $status){
        if ($username_ != $username) {
            $json['usernameResponse'] = '<font color="red">Please input a valid username</font>';
        }
        else {
            $sign_up_result = find('user',['username'=>$username]);
            if ($sign_up_result) {
                $json['usernameResponse'] = '<font color="red">This user name has existed!</font>';
            }
            else{
                insert('user',['username'=>$username,'password'=>$password,'indentity'=>'user']);
                $json['passwordResponse'] = '<font color="#7cfc00">Success</font>';
                $json['method'] = 'user';
            }
        }
    }
    $json = json_encode($json);
    print $json;
}


?>
