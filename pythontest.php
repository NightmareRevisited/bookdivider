<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2018/3/15 15:15
 */
require_once('mongoconnect.php');
require_once('unicode.php');

function unicode_encode($name)
{
    $name = iconv('UTF-8', 'UCS-2', $name);
    $len = strlen($name);
    $str = '';
    for ($i = 0; $i < $len - 1; $i = $i + 2)
    {
        $c = $name[$i];
        $c2 = $name[$i + 1];
        if (ord($c) > 0)
        {    // 两个字节的文字
            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
        }
        else
        {
            $str .= $c2;
        }
    }
    return $str;
}


$intro = str2unicode("看半小时漫画，通五千年历史，脉络无比清晰，看完就能倒背。仅仅通过手绘和段子，二混子就捋出清晰的历史大脉络：简到崩溃的极简欧洲史、美国往事三部曲、一口气就能读完的日本史、肌肉猛男斯巴达300勇士、酷炫无比的加勒比海盗……掀开纷繁复杂的历史表象，略去无关紧要的细枝末节，每一页都有历史段子，每三秒让你笑翻一次，而二混子手绘的历史人物则是又贱又蠢萌：亚历山大、恺撒、君士坦丁、查理曼大帝、华盛顿、林肯、拿破仑、明治天皇，全都和你我一样，有优点和缺陷，有朋友和敌人，他们在历史关键节点迸发出的惊人能量铸就了五千年的精彩世界史。而你在笑出腹肌的同时，不知不觉已经通晓了历史。");
$author = str2unicode("陈磊");
print $intro.'<br>'.$author.'<br>';
exec("C:/Python27/python.exe E:\pyworkspace\grad_design\gd\book_divide.py {$intro} {$author}",$output);

var_dump($output);

?>
