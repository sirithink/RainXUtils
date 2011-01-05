<?php


$post_data = <<<DATA
service=miniblog&client=ssologin.js%28v1.3.9%29&entry=miniblog&encoding=utf-8&gateway=1&savestate=7&from=&useticket=0&username=china_1982@163.com&password=504505&url=http%3A%2F%2Ft.sina.com.cn%2Fajaxlogin.php%3Fframelogin%3D1%26callback%3Dparent.sinaSSOController.feedBackUrlCallBack&returntype=META
DATA;

$post_url = "http://t.sina.com.cn/sso/login.php?client=ssologin.js(v1.3.9)";
$cookie = vlogin($post_url, $post_data);
$content = (get_content_by_cookie("http://t.sina.com.cn/pub/star?source=toptray", $cookie));

$find = '/<a class="title" href="http:\/\/t.sina.com.cn\/pub\/star\/sortshow.php\?sortid=(\d+)">([^<]*)<\/a>/';
preg_match_all($find, $content, $matches);

$r = "";
for ($i=0; $i< count($matches[0]); $i++) {
    $r .= $matches[1][$i] . "," . $matches[2][$i];
    $r .= "\n";
}

file_put_contents("./big_category.result.txt", $r);

$find = '/<a href="http:\/\/t.sina.com.cn\/pub\/star\/sort_letter.php\?sortid=(\d+)".*?target="_blank">([^<]*)<\/a>/';

preg_match_all($find, $content, $matches);
$r = "";

for ($i=0; $i< count($matches[0]); $i++) {
    $r .= $matches[1][$i] . "," . $matches[2][$i];
    $r .= "\n";
}

file_put_contents("./sub_category.result.txt", $r);


for ($i=0; $i< count($matches[0]); $i++) {
    get_star_data_by_cid($matches[1][$i], $cookie);
}

clear_cookie($cookie);

// --- self define function

function get_star_data_by_cid($cid, $cookie)
{
    $content = get_content_by_cookie("http://t.sina.com.cn/pub/star/sort_letter.php?sortid=$cid", $cookie);
    $find = '/<div class="name"><a href="medium.php\?uid=(\d+)" target="_blank" >([^<]*)<\/a><\/div>/';
    preg_match_all($find, $content, $matches);
    $r= "";
    for ($i=0; $i< count($matches[0]); $i++) {
        $r .= $matches[1][$i] . "," . $matches[2][$i];
        $r .= "\n";
    }
    file_put_contents("./{$cid}_star_data.result.txt", $r);

    $find = '/<img  imgtype="headimg" cid=".*?" uid="(\d+)" src="([^"]*)"  title="(.*?)"\/>/';
    preg_match_all($find, $content, $matches);

    $r="";
    for ($i=0; $i< count($matches[0]); $i++) {
        $r .= $matches[1][$i] . "," . $matches[2][$i];
        $r .= "\n";
    }
    file_put_contents("./{$cid}_star_head.result.txt", $r);
}


//-----------------------
   function vlogin($url,$request){
        $cookie_jar = tempnam('./tmp','cookie');//在当前目录下生成一个随机文件名的临时文件
        $ch = curl_init(); //初始化curl模块
        curl_setopt($ch,CURLOPT_URL,$url);//登录页地址
        curl_setopt($ch, CURLOPT_POST, 1);//post方式提交
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);//要提交的内容
        //把返回$cookie_jar来的cookie信息保存在$cookie_jar文件中
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        //设定返回的数据是否自动显示
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //设定是否显示头信息
        curl_setopt($ch, CURLOPT_HEADER, false);  
        //设定是否输出页面内容
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_exec ($ch); 
        curl_close($ch); //get data after login        
        return $cookie_jar;
    }
    
//登录成功后通过cookies获取页面内容
    function get_content_by_cookie($url,$cookie_jar){
        $ch2 = curl_init();        
        curl_setopt($ch2, CURLOPT_URL, $url);
        curl_setopt($ch2, CURLOPT_HEADER, false);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar);
        $orders=curl_exec($ch2);        
        curl_close($ch2); 
        return $orders;
    }

function clear_cookie($cookie_tmp_name){
        @unlink($cookie_tmp_name);
    }
