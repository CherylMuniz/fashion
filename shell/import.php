<?php

class Spider
{
        public $cookies;
        public $captcha;
        public $params;
        
        public function __construct(){
                $this->captcha = $this->captcha();
                //$params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&recaptcha_challenge_field={$this->captcha}&recaptcha_response_field=&submitted=";    //$params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&redirect=%2F&submitted=1";
                $url = "http://fotostrana.ru/user/ajaxlogin/";
                $this->params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&redirect=%2F&ajax=1&recaptcha_response_field=&recaptcha_challenge_field={$this->captcha}&tk=14624&json=0";    //$params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&redirect=%2F&submitted=1";
                $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17";
                $c_file = 'vcookie.txt';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$this->params);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

                curl_setopt($ch, CURLOPT_COOKIEJAR, $c_file);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $c_file);
                $cookie = '';

                $c_arr = file($c_file); //var_dump($c_arr);

                $matches = array();
                preg_match('/^.*ref_id\s+(\d+)/', $c_arr[4], $matches);
                $cookie['ref_id']=$matches[1];

                preg_match('/^.*\s+s\s+(.+)/', $c_arr[6], $matches);
                $cookie['s']=$matches[1];

                preg_match('/^.*searchParams\s+(.+)/', $c_arr[7], $matches);
                $cookie['searchParams']=$matches[1];

                //$this->cookies = 'sub_id=deleted&meetingPool=0&ref_id='.$cookie['ref_id'].'; s='.$cookie['s'].'; searchParams='.$cookie['searchParams'];
                $this->cookies = 'uid=11935779&sub_id=deleted&meetingPool=0&ref_id='.$cookie['ref_id'].'; s='.$cookie['s'].'; searchParams='.$cookie['searchParams']; //var_dump( $this->cookies );
                
                curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);

                $result=curl_exec($ch);
                curl_close ($ch);
        }
        
        public function preconstruct(){
            $url = "http://fotostrana.ru/user/login/";
            $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

                $result=curl_exec ($ch);
                //echo($result);
                curl_close ($ch);
        }
        
        public function captcha(){
            $url = "http://api.recaptcha.net/challenge?k=6LcKDwcAAAAAAAYz6J16NNZQoZY_8endUzPBBohD";
            $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                $result=curl_exec ($ch);
                $captcha = null;
                preg_match('/challenge.*\'(.*)\'/', $result, $captcha);
                $captcha = trim($captcha[1]);
                curl_close ($ch);
                return $captcha;
        }
        public function visit($fromPage, $toPage)
        {
                $ids = $this->collectIds($fromPage, $toPage);
                $count = sizeof($ids);
                echo "\n******Start******\n";
                echo "Users = {$count}\n";
                $percent = 100/$count;
                $i=0;
                foreach($ids as $id){
                        if("26295868" ==  $id) { echo "\nWarning!!! Elena) detected!!! Passed!\n"; continue; } 
                        $url = "http://fotostrana.ru/user/{$id}/";
                        //$url = $toUrl.$id;
                        $res = $this->spider($url);
                        //var_dump($res);
                        ++$i;
                        echo $id.' '.floor($i*$percent)."%\n";
                }
                echo "\nVisit {$count} users\n";
                echo "\n******** done! ***********\n";
        }
        
        public function collectIds($fromPage, $toPage){
                $url = 'http://fotostrana.ru/search/?ajax=true&gender=w&age=22&ageTo=29&cityId=8&advSelects%5B%5D=&newOnly=0&geoOnly=0&online=0';
                $ids = array();
                for($i=$fromPage; $i<$toPage; $i++){
                        $j = file_get_contents($url."&page=".$i);       //echo $url."&page=".$i."\n";
                        $x = json_decode($j);
                        preg_match_all("/\"user(\d+)\"/", $x->resultHtml, $matches);
                        $ids = array_merge($ids, $matches[1]);          //var_dump($ids);
                        //echo $i."\n";
                }
                return $ids;
        }
        
        public function spider($url, $post=false)
        {
                $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17";
                $c_file = 'vcookie.txt';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                if($post){
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
                }
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

                curl_setopt($ch, CURLOPT_COOKIEJAR, $c_file);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $c_file);

                curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);

                $result=curl_exec ($ch);
                //echo $result;
                curl_close ($ch);
                return $result;
        }

        public function guess($from, $to){
                
                $ids = $this->collectIds($from, $to);
                $count = sizeof($ids);
                echo "\n******Start******\n";
                echo "Users = ".$count." \n";
                $percent = 100/$count;
                $i=0;
                foreach($ids as $id){
                        if("26295868" ==  $id) { echo "\nWarning!!! Elena) detected!!! Passed!\n"; continue; } 
                        $nature = rand(12, 22);
                        $hobby = rand(39, 55);
                        $profession = rand(69, 80);
                        $url = "http://fotostrana.ru/guess/ajax/saveGuess?guess_user=".$id."&answer%5B1%5D=".$nature."&uanswer%5B1%5D=&answer%5B2%5D=".$hobby."&uanswer%5B2%5D=&answer%5B3%5D=".$profession."&uanswer%5B3%5D=&send_message=on&ajax=1&isPopup=0";
                //      echo $url."\n";
                        $res = $this->spider($url);
                //      var_dump($res);
                        ++$i;
                        echo floor($i*$percent)."%\n";
                        //sleep(rand(4, 7));
                        sleep(1);
                }
                echo "\nSend guess message to ".$count." users\n";
                echo "\n******** done! ***********\n";
        }
        public function meet(){
            $url = 'http://fotostrana.ru/meeting/?noredirect=1';        //#$this->spider('http://fotostrana.ru/meeting/?change=1&gender=&age=21&ageTo=29'); //set age limits
            $res = $this->spider($url);
            preg_match_all("/popupVipBuying\(3,0,null,\'(.*==)\',function/", $res, $matches);
            $uid = $matches[1][0];
            var_dump($uid);
            $urlVote = 'http://fotostrana.ru/meeting/index/click/?ajax=true&uId='.urlencode($uid).'&val='.rand(2, 3).'&rate=5&fake=0&uid='.urlencode($uid);
            $x = $this->spider($urlVote);
            $match = null;
            $error = preg_match('/"ret":0/', $x, $match );
            if($error){
                echo "error\n"; die;
                $this->spider('http://fotostrana.ru/meeting/');
            }
        }
        public function meeting(){
            for($i=0; $i<500; $i++){
                $this->meet();
                //sleep(rand(5, 7));
                usleep(300000);
                echo $i.' ';
            }
        }
        public function fotosym(){
            $url = 'http://foto5stars.ru/';
            $response = $this->spider($url);
            $match = null;
            preg_match('/params\[like_user_id\]/', $response, $match);
            var_dump($response);
            $url = 'http://foto5stars.ru/request/call/';
            //$like_user_id = 
            //$post = "action=user.like&params[like]=5&params[like_user_id]={$like_user_id}";
        }
}

$searchUrl = "http://fotostrana.ru/search/?cityId=8&otherCity=&gender=w&age=22&ageTo=28&height%5Bmetric%5D=0&height%5Benglish%5D=0&weight%5Bmetric%5D=0&weight%5Benglish%5D=0&newOnly=0&online=1&ajax=true&change=1";
//$url = "http://fotostrana.ru/profile/ajax/freeVote?value=1&ftoken-all=63655255ba&userId=30911742";
// $url = "http://fotostrana.ru/profile/ajax/freeVote/?userId=30911742&value=1&ftoken-all=63655255ba";
//$url = "http://fotostrana.ru/contest/vote/votedata/?userId=36306794&dir=up&nominationId=0&ftoken-f-contestSendVote-36306794=wfzzieiefc&ajax=1&sendFreeVote=1";
//$url = "http://fotostrana.ru/contest/new/votePopup/_ajax=1&ajax=1&userId=29504825&from=profile";
//$url = "http://fotostrana.ru/contest/new/votePopup/_ajax=1&ajax=1&userId=206878&from=profile";
//$url = "http://fotostrana.ru/contest/new/votePopup/_ajax=1&ajax=1&userId=36429493&from=profile";
//$url = "http://fotostrana.ru/contest/new/votePopup/?_ajax=1&ajax=1&userId=39607735&from=profile";
$guessurl = "http://fotostrana.ru/guess/ajax/saveGuess?guess_user=39402139&answer%5B1%5D=16&uanswer%5B1%5D=&answer%5B2%5D=41&uanswer%5B2%5D=&answer%5B3%5D=17992&uanswer%5B3%5D=&send_message=on&ajax=1&isPopup=0";
$guessurl = "http://fotostrana.ru/guess/ajax/saveGuess?guess_user=39402139&answer%5B1%5D=16&uanswer%5B1%5D=&answer%5B2%5D=41&uanswer%5B2%5D=&answer%5B3%5D=17992&uanswer%5B3%5D=&ajax=1&isPopup=0";
$searchUrl = '';
$sp = new Spider();
$url = "http://fotostrana.ru/user/autologin/?u=11935779&h=10aaaec8776a62a";
$x = $sp->spider($url);

$url = "http://fotostrana.ru/user/11935779";
//$x = $sp->spider($url);
//$sp->collectIds(1,2);
//$sp->visit($argv[1],$argv[2]);
$sp->meeting();
//$sp->guess(10,20);
//$sp->guess(21,30);
//$sp->guess($argv[1],$argv[2]);
//$sp->guess($argv[1],$argv[2]);
//$x = $sp->visit($argv[1],$argv[2]);

//$y = json_decode($x);
//echo $y->html;
//var_dump($y);
//~ $sp->visit($argv[1],$argv[2]);

//~ $j = file_get_contents($url);
//~ $x = json_decode($j);
//~ var_dump($j);
