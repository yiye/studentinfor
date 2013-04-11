<?php 
/** 
  * 
  */
   require_once('action.searchbook.php');
   require_once('handleMem.php');
   require_once('userDB.php');
   require_once('newsDB.php');
    //define your token 
    define("TOKEN", "yiye");
   $wechatObj = new wechatCallbackapiTest(); 
  // $wechatObj->valid();//验证完成后可将此行代码注释掉 
 	//$wechatObj->responseMsg();
    class wechatCallbackapiTest 
    { 
        private $user; 
        private $key; 
        public function valid() 
        { 
            $echoStr = $_GET["echostr"]; 
       
            //valid signature , option 
            if($this->checkSignature()){ 
                echo $echoStr; 
                exit; 
            } 
        } 
       
        public function responseMsg() 
        { 
            //get post data, May be due to the different environments 
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; 
            //extract post data 
            if (!empty($postStr)){ 
                       
                    $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); 
                    $this->user = $fromUsername = $postObj->FromUserName; 
                                  $toUsername = $postObj->ToUserName;
                    $this->key = $keyword = trim($postObj->Content); 
                    $time = time(); 
                    /*$textTpl = "<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>
								<Content><![CDATA[%s]]></Content>
								<FuncFlag>0</FuncFlag>
								</xml>"; */
                $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                %s
                <FuncFlag>0</FuncFlag>
                </xml>";
                    if(!empty( $keyword )) 
                    { 
                      $content = "";

                        if ($keyword == "1"){

                          $content = $this->getNews($fromUsername); 
                         } elseif (preg_match_all("#^<.*>#", $keyword,$temp)) {
                           $content = $this->searchBook($fromUsername,$temp[0][0]);
                         } elseif (preg_match_all("#^>\d#i", $keyword,$temp)) {
                           $content = $this->nextPageBook($fromUsername,$temp[0][0]);  
                         }else{
                           $content = "<MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[回复 1 查看学生周知,回复 2+书名 进行数目检索,如果想聊天请关注isyiye]]></Content>";
                         }
                          
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time,$content); 
                        echo $resultStr; 
                    }else{ 
                        echo "Input something..."; 
                    } 
       
            }else { 
                echo ""; 
                exit; 
            } 
        } 
               
        private function checkSignature() 
        { 
            $signature = $_GET["signature"]; 
            $timestamp = $_GET["timestamp"]; 
            $nonce = $_GET["nonce"];     
                       
            $token = TOKEN; 
            $tmpArr = array($token, $timestamp, $nonce); 
            sort($tmpArr); 
            $tmpStr = implode( $tmpArr ); 
            $tmpStr = sha1( $tmpStr ); 
               
            if( $tmpStr == $signature ){ 
                return true; 
            }else{ 
                return false; 
            } 
        }
        /*
        *获取新闻, 用户没查找一次新闻会在数据库中给用户存放一个他获取的最新的新闻的ID
        *下次再获取时,只返回比这个新闻ID更新的学生周知
        *返回值为相应的xml格式字符串
        *<xml>
        *   <ToUserName><![CDATA[toUser]]></ToUserName>
        *   <FromUserName><![CDATA[fromUser]]></FromUserName>
        *   <CreateTime>12345678</CreateTime>
        *===================以下部分由本函数组装=============
        *   <MsgType><![CDATA[news]]></MsgType>
        *   <ArticleCount>2</ArticleCount>
        *   <Articles>
        *     <item>
        *       <Title><![CDATA[title1]]></Title> 
        *       <Description><![CDATA[description1]]></Description>
        *       <PicUrl><![CDATA[picurl]]></PicUrl>
        *       <Url><![CDATA[url]]></Url>
        *     </item>
        *     <item>
        *       <Title><![CDATA[title]]></Title>
        *       <Description><![CDATA[description]]></Description>
        *       <PicUrl><![CDATA[picurl]]></PicUrl>
        *       <Url><![CDATA[url]]></Url>
        *     </item>
        *   </Articles>
        *===================以上部分由本函数组装==============
        *   <FuncFlag>1</FuncFlag>
        *</xml> 
        */
        public function getNews($user)
        {
          $userDB = new UserDB();
          $lastNews = $userDB->getLastNews($user);
          $newsDB = new newsDB();
          $newslist = $newsDB->get_newslist_by_times(1);
          // print_r($newslist);
          $unreadNember = 0;
          //对返回的新闻数组进行剪裁
          if ($lastNews) {
            foreach ($newslist as $key => $value) {
              if ($value['id'] <= (int)$lastNews) {
                  array_splice($newslist, $key);
               break;
              }
            }
          } else {
            array_pop($newslist);
          }
          //保证 newlist 小于 9条
          if (count($newslist)>=10) {
            # code...
            array_pop($newslist);
            //将最后一条换成提示用户还剩几条没看...

            $tmep = array_pop($newslist);
            $unreadNember = (int)$tmep["id"]-(int)$lastNews;
            $temp['id'] = "0000";
            $temp["origin"] = "ssdlut";
            $temp["title"] = "提示:你还有".$unreadNember."条学生周知没看";
            array_push($newslist, $temp);
          }
          //将最新的id存入数据库
          if ($newslist[0]['id']!=null) {
            # code...
             $userDB->updateNews($user,$newslist[0]['id']);
          }else{
            return "<MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[没有新的学生周知]]></Content>";
          }
         //将newlist 转换成 xml格式
          /*

          */
          $arrLogo = array('创新实践基地　　' => 1,
                          '创新实践基地　　' => 2,
                          '学院分党委　　' => 3,
                          '团委　　' => 4,
                          '体育馆　　' => 5,
                          '图书馆　　' => 6,
                          '学生工作办公室　　' => 7,
                          '实训基地办公室　　' => 8,
                          'ssdlut' =>9);

          $articleCount = count($newslist)+1;
          $article = "<MsgType><![CDATA[news]]></MsgType>
          <ArticleCount>%s</ArticleCount>
            <Articles>
              %s
            </Articles>
          ";
          $item = "<item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
              </item>
          ";
          $itemlist = "<item>
                <Title><![CDATA[软件学院]]></Title>
                <Description><![CDATA[体育馆]]></Description>
                <PicUrl><![CDATA[http://ssdlut.duapp.com/img/headpic1.jpg]]></PicUrl>
                <Url><![CDATA[http://ssdlut.duapp.com/img/headpic1.jpg]]></Url>
              </item>";
          foreach ($newslist as $key => $value) {
            $title = $value["title"];
            $logoId = $arrLogo[$value["origin"]];
            $picUrl = "http://ssdlut.duapp.com/img/logo$logoId.png";
            if ($value['id']=="0000") {
              $url = "http://ssdlut.duapp.com/index.simple.php";
            } else {
            $url = "http://ssdlut.duapp.com/detail.simple.php?id=".$value['id'];
             
            }
            
            $newItem = sprintf($item,$title,$title,$picUrl,$url);
            $itemlist .=$newItem;

          }
          return sprintf($article,$articleCount,$itemlist);
        }
        public function nextPageNews($value='')
        {
          # code...
        }
        /*
        *查询图书查询
        *期望的q为查询的内容
        *<xml>
        *<ToUserName><![CDATA[toUser]]></ToUserName>
        *<FromUserName><![CDATA[fromUser]]></FromUserName>
        *<CreateTime>12345678</CreateTime>
        *==================以下部分由本函数进行返会========
        *<MsgType><![CDATA[text]]></MsgType>
        *<Content><![CDATA[content]]></Content>
        *==================以上部分=======================
        *<FuncFlag>0</FuncFlag>
        *</xml>
        */
        /*
        ---------------------------------
        jQuery 指南
        索书号:TP31398JA
        XX著 /XXXX出版社/2012
        馆藏/可借: 4/1
        */
        public function searchBook($user,$q)
        {
          $search = new SearchBook($user,$q);
          $five = $search->getFive();
          $result = "<MsgType><![CDATA[text]]></MsgType>
                     <Content><![CDATA[%s]]></Content>";
          $content = "";
          if ($five) {
            $item = "----------------------
----------------------
%s
%s
%s
%s
";
            
            foreach ($five as $key => $value) {
              $newItem = sprintf($item,$value["name"],$value["number"],$value["publish"],$value["msg"]);
              $content .= $newItem;
            }
          } else {
            $content = " 没有查到结果";
          }
          $result = sprintf($result,$content);
          return $result;
        }
        /*
        *查询图书查询
        *期望的q="p2"向下翻页的页数
        *<xml>
        *<ToUserName><![CDATA[toUser]]></ToUserName>
        *<FromUserName><![CDATA[fromUser]]></FromUserName>
        *<CreateTime>12345678</CreateTime>
        *==================以下部分由本函数进行返会========
        *<MsgType><![CDATA[text]]></MsgType>
        *<Content><![CDATA[content]]></Content>
        *==================以上部分=======================
        *<FuncFlag>0</FuncFlag>
        *</xml>
        */
        /*
        ---------------------------------
        jQuery 指南
        索书号:TP31398JA
        XX著 /XXXX出版社/2012
        馆藏/可借: 4/1
        */
        public function nextPageBook($user,$q)
        {
          $page = (int)substr($q, 1,1); 
          $c = new HandleMem();
          $five = $c->getBooksByTimes($user,$page);
          $result = "<MsgType><![CDATA[text]]></MsgType>
                     <Content><![CDATA[%s]]></Content>";
          $content = "";
          if ($five) {
            $item = "----------------------
----------------------
%s
%s
%s
%s
";
            
            foreach ($five as $key => $value) {
              $newItem = sprintf($item,$value["name"],$value["number"],$value["publish"],$value["msg"]);
              $content .= $newItem;
            }
          } else {
            $content = "图书检索结果已经失效,请重新进行查询";
          }
          $result = sprintf($result,$content);
          return $result;
        }
    } 

    /*
    test
    */
    
//var_dump($wechatObj->getNews("232ljwlefjsdnf"));
//var_dump($wechatObj->searchBook("232ljwlefjsdnf","jQuery"));
//  var_dump($wechatObj->nextPageBook("232ljwlefjsdnf","p3"));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
 <?php
  //var_dump($wechatObj->getNews("232ljwlef"));
//var_dump($wechatObj->searchBook("otdW-jmDXxlfL1vHMzJO-P0pYBvQ","数据结构"));
var_dump($wechatObj->nextPageBook("otdW-jmDXxlfL1vHMzJO-P0pYBvQ",">2"));
 ?>
</body>
</html>