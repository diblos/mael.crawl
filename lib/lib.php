<?php

define("ENV", "environment.json", true);
define("YAPI", "http://query.yahooapis.com/v1/public/yql", true);

ini_set('error_reporting', E_ERROR);
ini_set('display_errors', '1');
ini_set('date.timezone', 'Asia/Kuala_Lumpur');
// ini_set('date.timezone', 'UTC');

ini_set('always_populate_raw_post_data', '-1');//PHP Deprecated:  Automatically populating $HTTP_RAW_POST_DATA is deprecated and will be removed in a future version. To avoid this warning set 'always_populate_raw_post_data' to '-1' in php.ini and use the php://input stream instead. in Unknown on line 0

function getEnvironment($url,$listcode)
{
    $contents = file_get_contents($url);
    $json =json_decode($contents);
    // var_dump($json[$listcode-1]);
    return $json[$listcode-1];
}

function queryEnvironment($env){
  try {
      $url = $env->url;
      $path = $env->xpath;
      $doc = $env->documentType;
      //Code to access YQL using PHP
      $yql_query = "select ".(($env->object == '') ? "*" : $env->object)." from ".$doc." where url='".$url."'".(($path == '') ? "" : " and xpath='".$path."'");
      return getResultFromYQL(sprintf($yql_query),'store%3A%2F%2Fdatatables.org%2Falltableswithkeys');
  } catch (Exception $e) {
      echo "ERR: queryEnvironment >".$e->messaga ;
      return false;
  }
}

// Function to get the client ip address
function get_client_ip_server() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';

    return $ipaddress;
}

function url_origin( $s, $use_forwarded_host = false )
{
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}

function full_url( $s, $use_forwarded_host = false )
{
    return url_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
}

function getResultFromYQL($yql_query, $env = '') {
    $yql_query_url = YAPI . "?q=" . urlencode($yql_query);
    $yql_query_url .= "&format=json";

    if ($env != '') {
        $yql_query_url .= '&env=' . urlencode($env);
    }

    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    //Uncomment if you are behind a proxy
    //curl_setopt($session, CURLOPT_PROXY, 'Your proxy url');
    //curl_setopt($session, CURLOPT_PROXYPORT, 'Your proxy port');
    //curl_setopt($session, CURLOPT_PROXYUSERPWD, 'Your proxy password');
    $json = curl_exec($session);
    curl_close($session);
    // return json_decode($json);
    return $json;
}

function CUTITOUT($result,$str){

    for ($i = 0; $i < count($result->query->results->pre->a); ++$i) {
        if (strpos($result->query->results->pre->a[$i]->content, $str) !== false){
            $nkey = $i;
        }
    }
    unset($result->query->results->pre->a[$nkey]);

}

function GetDisplayName($prefix){
    global $HCODE;
    $json= json_decode($HCODE);

    foreach($json AS $mydata)
    {
      if($mydata->highwayCode === strtoupper($prefix)){
        $return_val = $mydata->content;
        break;
      }
    }

    return $return_val;
}

function CameraName($ndata){

    for ($i = 0; $i < count($ndata->query->results->pre->a); ++$i) {

            $ndata->query->results->pre->a[$i]->href = CAMROOT.$ndata->query->results->pre->a[$i]->href;
            $content=$ndata->query->results->pre->a[$i]->content;
            $content=substr($content,4);
            $content=str_replace('_',' ',$content);
            $content=str_replace('CAM','CAMERA',$content);
            $content=str_replace('WB','(WEST BOUND)',$content);
            $content=str_replace('EB','(EAST BOUND)',$content);
            $content=str_replace('SB','(SOUTH BOUND)',$content);
            $content=str_replace('NB','(NORTH BOUND)',$content);
            $content=str_replace(' EXT',' (EXIT)',$content);
            $content=str_replace(' ENT',' (ENTRANCE)',$content);
            $content=str_replace('.web.jpg','',$content);
            $ndata->query->results->pre->a[$i]->content=$content;
            $ndata->query->results->pre->a[$i]->timestamp=$ndata->query->results->pre->content[$i-1];

    }
}

function SETTLEDATE($txt){
    $re1='((?:Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday|Tues|Thur|Thurs|Sun|Mon|Tue|Wed|Thu|Fri|Sat))';    # Day Of Week 1
    $re2='(.)';   # Any Single Character 1
    $re3='(\\s+)';    # White Space 1
    $re4='((?:Jan(?:uary)?|Feb(?:ruary)?|Mar(?:ch)?|Apr(?:il)?|May|Jun(?:e)?|Jul(?:y)?|Aug(?:ust)?|Sep(?:tember)?|Sept|Oct(?:ober)?|Nov(?:ember)?|Dec(?:ember)?))';   # Month 1
    $re5='(\\s+)';    # White Space 2
    $re6='((?:(?:[0-2]?\\d{1})|(?:[3][01]{1})))(?![\\d])';    # Day 1
    $re7='(.)';   # Any Single Character 2
    $re8='(\\s+)';    # White Space 3
    $re9='((?:(?:[1]{1}\\d{1}\\d{1}\\d{1})|(?:[2]{1}\\d{3})))(?![\\d])';  # Year 1
    $re10='(\\s+)';   # White Space 4
    $re11='((?:(?:[0-1][0-9])|(?:[2][0-3])|(?:[0-9])):(?:[0-5][0-9])(?::[0-5][0-9])?(?:\\s?(?:am|AM|pm|PM))?)';   # HourMinuteSec 1

      if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7.$re8.$re9.$re10.$re11."/is", $txt, $matches))
      {
          $dayofweek1=$matches[1][0];
          $c1=$matches[2][0];
          $ws1=$matches[3][0];
          $month1=$matches[4][0];
          $ws2=$matches[5][0];
          $day1=$matches[6][0];
          $c2=$matches[7][0];
          $ws3=$matches[8][0];
          $year1=$matches[9][0];
          $ws4=$matches[10][0];
          $time1=$matches[11][0];
          // print "($dayofweek1) ($c1) ($ws1) ($month1) ($ws2) ($day1) ($c2) ($ws3) ($year1) ($ws4) ($time1) \n";
          // print "$dayofweek1$c1$ws1$month1$ws2$day1$c2$ws3$year1$ws4$time1\n";
          // var_dump($matches);

          // foreach ($matches[0] as $key => $value){
          //       echo trim($value).' | ';
          // }

      }

    return $matches[0];

}

function ProcessResult($result,$id){
    switch ($id) {
      case 9:
          $r = new stdClass();
          $r = json_decode($result);
          $r = new BotnetObj09(json_decode($result));

          echo (json_encode($r));

          break;
      case 10:
        	$r = new stdClass();
        	$r = json_decode($result);
          $r = new BotnetObj10(json_decode($result));

          echo (json_encode($r));

          break;
      default:
          echo($result);
    }
}
// BOTNET 9
class BotnetObj09 {
    public function __construct($obj) {
        $this->query->count = count($obj->query->results->rss->channel->item);
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->results->item = $obj->query->results->item;
        foreach($this->query->results->item AS $mydata)
        {
            $mydata->guid = explode("&id=",$mydata->guid)[1];
        }
    }
}

// BOTNET 10
class BotnetObj10 {
    public function __construct($obj) {
        $this->query->count = count($obj->query->results->rss->channel->item);
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->results->item = $obj->query->results->rss->channel->item;
        foreach($this->query->results->item AS $mydata)
        {
            $mydata->guid = $mydata->guid->content;
        }
    }
}

// LLMTRAFIK OBJECTS
class HighwayList {
    public function __construct($obj) {
        $this->query->count = $obj->query->count;
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->results->highway = $obj->query->results->option;
        foreach($this->query->results->highway AS $mydata)
        {
            $mydata->prefix = $mydata->value;
            $mydata->name = $mydata->content;
            unset($mydata->value);
            unset($mydata->content);
        }
    }
}

class CameraList0 {
    public function __construct($obj) {
        $this->query->count = $obj->query->count;
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->results->camera = $obj->query->results->span;
        if(!is_array($this->query->results->camera)){
            $this->query->results->camera = (array) $this->query->results->camera;
        }
    }
}

class CameraList {
    public function __construct($obj) {
        $this->query->count = $obj->query->count;
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->results->camera = $obj->query->results->a;
        foreach($this->query->results->camera AS $mydata)
        {
            $mydata->image = $mydata->href;
            $mydata->name = $mydata->content;
            unset($mydata->href);
            unset($mydata->content);
        }
    }
}

class CameraList2 {
    public function __construct($obj) {
        $this->query->count = $obj->query->count;
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->prefix = $obj->query->prefix;
        $this->query->name = $obj->query->name;
        $this->query->results->camera = $obj->query->results->pre->a;
        foreach($this->query->results->camera AS $mydata)
        {
            $mydata->image = $mydata->href;
            $mydata->name = $mydata->content;
            $mydata->timestamp = $mydata->timestamp;
            unset($mydata->href);
            unset($mydata->content);
        }
    }
}

// MICNE OBJETCS
class CinemaList {
    public function __construct($obj) {
        $this->query->count = $obj->query->count;
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        $this->query->results->cinema = $obj->query->results->option;
        foreach($this->query->results->cinema AS $mydata)
        {
            $mydata->code = $mydata->value;
            $mydata->name = $mydata->content;
            unset($mydata->value);
            unset($mydata->content);
        }
    }
}

class ShowList {
    public function __construct($obj) {
        $this->query->count = $obj->query->count;
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;

        $this->query->results->shows = $obj->query->results->div;

        foreach($this->query->results->shows AS $mydata)
        {
            $mydata->title = $mydata->a->b;
            $mydata->link = $mydata->a->href;
            $mydata->description = $mydata->i;
            $mydata->rate = trim($mydata->content);

            if(!is_array($mydata->div)){
                $mydata->div = (array) $mydata->div;
            }

            $mydata->times = $mydata->div;
            unset($mydata->id);
            unset($mydata->br);
            unset($mydata->content);
            unset($mydata->a);
            unset($mydata->i);
            unset($mydata->div);

            if(!is_array($mydata->times->a)){
                $mydata->times->a = (array) $mydata->times->a;
            }

            $tmp = [];
            foreach($mydata->times AS $my){
                if(isset($my->a)){
                    array_push($tmp, $my->a->content);
                    unset($my->a);
                }
            }
            if(sizeof($tmp)>0){$mydata->times = $tmp;};
        }

        // ridiculous hacks starts
        $this->query->results->shows->a->title = $this->query->results->shows->a->b;
        $this->query->results->shows->a->link = $this->query->results->shows->a->href;
        $this->query->results->shows->a->rate = trim($this->query->results->shows->content);
        $this->query->results->shows->a->description = $this->query->results->shows->i;
        $this->query->results->shows->a->times = $this->query->results->shows->div;
        unset($this->query->results->shows->a->href);
        unset($this->query->results->shows->a->b);
        unset($this->query->results->shows->id);
        unset($this->query->results->shows->br);
        unset($this->query->results->shows->content);
        unset($this->query->results->shows->i);
        unset($this->query->results->shows->div);

        $r = new stdClass();
        $r = $this->query->results->shows->a;
        if(isset($r)) {
            unset($this->query->results->shows->a);
            $this->query->results->shows = [];
            array_push($this->query->results->shows, $r);
        }
        // ridiculous hacks ends

    }
}
?>
