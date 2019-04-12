<?php
function dd($val){
    echo '<pre>';
    print_r($val);die;
}

$BookMarks = isset($_COOKIE['BookMarks']) ? $_COOKIE['BookMarks'] : '';
if (isset($_POST['page'])) {
    setcookie('BookMarks', substr($_POST['page'],strpos($_POST['page'],'=')+1));
}

if (isset($BookMarks) && $BookMarks && !isset($_GET['p'])) {
    $_GET['p'] = $BookMarks;
}

$p="https://www.biquwu.cc";
// $p=isset($_GET['p'])?($p.$_GET['p']):($p."/biquge/2_2654/c16114450.html");
$p=isset($_GET['p'])?($p.$_GET['p']):$p;
function getContent($url = "https://www.biquwu.cc"){
	//$url = "https://www.biquwu.cc/biquge/2_2654/c16114450.html";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// 避免301
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	//执行并获取HTML文档内容
	$output = curl_exec($ch);
    // $output = file_get_contents($url);
	$output = mb_convert_encoding($output, 'utf-8', 'gbk');

	//匹配标题
	$ptn = '/<h1>.*?<\/h1>/ism';
	preg_match($ptn,$output,$title);
    
	/*// 匹配分类下的最新书单
    $newPtn = '/<div class=\"l\">.*<\/div>/ism';
    preg_match($newPtn,$output,$newBooks);

    // 匹配分类下的最热书单
    $hotPtn = '/<div class=\"r\">.*<\/div>/ism';
    preg_match($hotPtn,$output,$hotBooks);*/

    // 当前分类下的所有书单
    $booksPtn = '/<div id=\"newscontent\">.+<\/div>/ism';
    preg_match($booksPtn,$output,$Books);


    // 去除底部 友情链接
    $linkPtn = '/<div id=\"firendlink\">.*?<\/div>/ism';
    preg_match($linkPtn,$output,$link);


    // 去除底部版权
    $footerPtn = '/<div class=\"footer\">.*?<\/div>/ism';
    preg_match($footerPtn,$output,$footer);



	// 匹配分类
    // $categoryPtn = '/<li>.*<\/li>/ism';
    $categoryPtn = '/<div class=\"nav\">.*?<\/div>/ism';
    preg_match($categoryPtn,$output,$category);

    // 去除首页
    // $categoryIndexPtn = '/<li>.*?<\/li>/';
    // preg_match($categoryIndexPtn,$output,$categoryIndex);
    // $category = str_replace($categoryIndex, ' ',$category[0]);

	// 匹配所有章节
    $list = '/<dd>.*<\/dd>/';
    preg_match($list,$output,$lists);

	//匹配内容
	$ptn2 = '/<div id=\"content\">.*?<\/div>/';
	preg_match($ptn2,$output,$content);

	//获取上一页链接
    //$ptn3 = '/<h3><a href="([^"]+)">上一页<\/a>(.+?)<a href="([^"]+)" title="([^"]+)">[^<]+<\/a>(.+?)<a href="([^"]+)">下一页<\/a><\/h3>/';
    //$ptn3 = '/<a href="([^"]+)">上一章<\/a>"←"<a href="([^"]+)">章节目录<\/a>"→"<a href="([^"]+)">下一章<\/a><a rel="nofollow" href="([^"]+)" >加入书签<\/a>/';
    $ptn3 = '/<a href="([^"]+)">上一章<\/a> &larr; <a href="([^"]+)">章节目录<\/a> &rarr; <a href="([^"]+)">下一章<\/a>/';
    //$ptn3 = '/<a rel="nofollow" href="([^"]+)" onclick="addBookMark([\d,]+);">加入书签<\/a>/';

    preg_match($ptn3,$output,$page);

    $data = [];
    $data['title'] = '';
    $data['content'] ='';
    $data['category'] = '';

    // 标题
    if (count($title) > 0) {
        $data['title'] =$title[0] ;
    }

    // 分类
    if (count($category) > 0) {
        $data['category'] = $category[0];
    }

    // 书单
    if (count($Books) > 0) {
        $data['content'] = str_replace($footer,'',$Books[0]);
        $data['content'] = str_replace($link,'',$data['content']);
    }

    if (count($lists) > 0 && count($Books) == 0) {
          // 匹配推荐内容
          $ptn4 = '/<dd>.*?<\/dd>/';
         // $ptn4="推荐鱼人的新书《极品修真强少》";
        preg_match($ptn4,$lists[0],$info);
        $data['content'] = str_replace($info,' ',$lists[0]);
    }

    // 内容
    if (count($content) > 0) {
        $data['content'] =$content[0] ;
    }


    if (count($page) > 0) {
        $data['prev'] =$page[1];
        $data['list'] =$page[2];
        $data['next'] =$page[3];
    }

	//释放curl句柄
	curl_close($ch);

    return $data;
	
}

$result = getContent($p);
/*echo "<pre>";
print_r($result);
echo "<pre>";
print_r($result);
 echo <<<EOF
 	<div class=\"header\">{$result['title']}</div>
 	<div class=\"content\">{$result['content']}</div>
 	<div class=\"header\"><a href=\"?p={$result['prev']}\">上一页</a>|<a href=\"?p={$result['next']}\">下一页</a></div>
 EOF;*/
?>
<style type="text/css"> 
	.header{text-align: center;}
	.content{font-size:14px;margin:0 auto;}
    dd{display: inline-block;width:20%;}
    .category li{width:8%;display: inline-block;padding: 0;margin-left:0;}
    li{width:39%;display: inline-block;padding: 10px; margin-left:8.7%;}
    h2{text-align: center;}

</style>
<!-- <h2 class="header">该文由风亲自抓取</h2> -->
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- <meta charset="GBK"> -->
	<meta http-equiv="Content-Type" content="text/html; charset=">
	<title>安心读</title>
    <script src="./js/jquery-2.2.3.js"></script>
    <script>
        function insertBookMarks() {
            var page=window.location.search;//当前请求的url的参数部分
            $.ajax({
                type: "post",
                url: "index.php",
                data:{"page":page},
                success: function(msg){
                    alert('添加成功，下次可继续从当前页开始阅读！');
                }
            });
        }
    </script>
</head>
<body>
	<div class="header">
        <div class="category"><?php echo $result['category'];?></div>
	<?php 
		if($result['title']){
			echo $result['title'];
	};?></div>
	<div class="content"><?php echo $result['content'];?></div>
    <div class="header"><a href="index.php?p=<?php echo $result['prev'];?>">上一页</a>|<a href="index.php?p=<?php
        echo $result['list'];?>">目录</a>|<a href="index.php?p=<?php echo $result['next'];?>">下一页</a>
        <a href="javascript:void(0);"><span onclick="insertBookMarks();">添加书签</span></a><a href="javascript:void(0);">
            <!-- <span onclick="gotoBooksCity();">进入书城</span></a> --></div>
</body>
</html>
<script type="text/javascript">
    // 点击章节查看文章
    $('.content dd').click(function(){
        $(this).find("a").attr('href','index.php?p=/biquge/2_2654/'+$(this).find("a").attr('href'));
    });
    // 查看分类书单
    $('.category li').click(function(){
        var category = $(this).text();
        // 获取分类名称
        if (category == '首页') {
            $(this).find("a").attr('href','index.php')
        } else {
            $(this).find("a").attr('href','index.php?p='+$(this).find("a").attr('href'));
        }
    });
    // 分类书单查看
    $('#newscontent span').click(function(){
        $(this).find("a").attr({'href':'index.php?p='+$(this).find("a").attr('href'),'target':'_self'});
    });
</script>



