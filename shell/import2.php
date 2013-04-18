<?php

class Spider
{
        public $cookies;
        public $url;
        
        public function __construct($searchUrl = ''){
                $url = "http://fotostrana.ru/user/login/"; //$url = "http://fotostrana.ru/user/ajaxlogin/";
                $params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&recaptcha_response_field=&redirect=%2Fuser%2Flogin%2F&submitted=1";    //$params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&redirect=%2F&submitted=1";
                $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17";
                $c_file = 'vcookie.txt';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

                curl_setopt($ch, CURLOPT_COOKIEJAR, $c_file);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $c_file);
                $cookie = '';

                $c_arr = file($c_file);
//var_dump($c_arr);
                $matches = array();
                preg_match('/^.*ref_id\s+(\d+)/', $c_arr[4], $matches);
                $cookie['ref_id']=$matches[1];

                preg_match('/^.*\s+s\s+(.+)/', $c_arr[6], $matches);
                $cookie['s']=$matches[1];

                preg_match('/^.*searchParams\s+(.+)/', $c_arr[7], $matches);
                $cookie['searchParams']=$matches[1];

                $this->cookies = 'sub_id=deleted&meetingPool=0&ref_id='.$cookie['ref_id'].'; s='.$cookie['s'].'; searchParams='.$cookie['searchParams'];
                $this->cookies = 'uid=11935779&sub_id=deleted&meetingPool=0&ref_id='.$cookie['ref_id'].'; s='.$cookie['s'].'; searchParams='.$cookie['searchParams'];
                
//var_dump( $this->cookies );
                curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);

                $result=curl_exec ($ch);
                //var_dump($result);
                curl_close ($ch);
                $this->url = $searchUrl;
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
                echo($result);
                curl_close ($ch);
        }
        
        public function visit($from, $to)
        {
                $ids = $this->collectIds($from, $to);
                $count = sizeof($ids);
                echo "\n******Start******\n";
                echo "Users = ".$count." \n";
                $percent = 100/$count;
                $i=0;
                foreach($ids as $id){
                        if("26295868" ==  $id) { echo "\nWarning!!! Elena) detected!!! Passed!\n"; continue; } 
                        $url = "http://fotostrana.ru/user/".$id."/";
                        //$url = $toUrl.$id;
                        echo $url."\n";
                        $res = $this->spider($url);
                        //var_dump($res);
                        ++$i;
                        echo $i*$percent." %\n";
                }
                echo "\nVisit ".$count." users\n";
                echo "\n******** done! ***********\n";
        }
        
        
        public function collectIds($from, $to){
                $ids = array();
                for($i=$from; $i<$to; $i++){
                        $j = file_get_contents($this->url."&page=".$i);
                        //~ echo $this->url."&page=".$i."\n";
                        $x = json_decode($j);
                        preg_match_all("/\"user(\d+)\"/", $x->resultHtml, $matches);
                        $ids = array_merge($ids, $matches[1]);
                        echo $i."\n";
                }
                return $ids;
        }
        
        public function spider($url)
        {
                $params = "user_email=fedoruck%40rambler.ru&user_password=kr322320&redirect=%2F&submitted=1";
                $user_agent = "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.17) Gecko/20110420 Firefox/3.6.17";
                $c_file = 'vcookie.txt';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

                curl_setopt($ch, CURLOPT_COOKIEJAR, $c_file);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $c_file);

                curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);

                $result=curl_exec ($ch);
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
                        echo $i*$percent." %\n";
                        sleep(rand(5, 10));
                }
                echo "\nSend guess message to ".$count." users\n";
                echo "\n******** done! ***********\n";
        }
        public function meeting(){
            //$url = 'http://fotostrana.ru/meeting/';
            $url = 'http://fotostrana.ru/usercontact/index/guest/?fromSideMenu=1';
            $res = $this->spider($url);
            echo $res;
            die;
            preg_match_all("/showVipBuyingPopup\(3,0,null,\'(.*==)\',function/", $res, $matches);
            //$uid = $matches[1][0];
            $urlVote = 'http://fotostrana.ru/meeting/index/click/?ajax=true&uId='.$uid.'&val=3&rate=5&fake=0&uid='.$uid;
            $setAgeUrl = 'http://fotostrana.ru/meeting/?change=1&gender=&age=21&ageTo=25';
            $x = $this->spider($setAgeUrl);
            //var_dump($matches[1][0]);
            var_dump($res);
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
$sp = new Spider($searchUrl);
$sp->preconstruct();
//$sp->meeting();
//$sp->guess($argv[1],$argv[2]);
//$x = $sp->visit($argv[1],$argv[2]);
//$x = $sp->spider($url);

//$y = json_decode($x);
//echo $y->html;
//var_dump($y);
//~ $sp->visit($argv[1],$argv[2]);

//~ $j = file_get_contents($url);
//~ $x = json_decode($j);
//~ var_dump($j);
