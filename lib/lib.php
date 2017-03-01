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

function log_defacement($item){
  $d = strtotime($item->listdate);
  // echo ">>$d".PHP_EOL;
	$db = connect_db();
	$query=mysqli_query($db,"REPLACE INTO defacement (attacker,team,homepage_deface,mass_deface,re_deface,special_deface,location,domain,os,listdate) VALUES ('$item->attacker' , '$item->team','$item->homepage_deface','$item->mass_deface','$item->re_deface','$item->special_deface','$item->location','$item->domain','$item->os',from_unixtime($d));");
  // $query=mysqli_query($db,"REPLACE INTO defacement (attacker,team,location,domain,os) VALUES ('$item->attacker' , '$item->team','$item->location','$item->domain','$item->os');");
	$db->close();
}

function log_phishing($item){
  $d = strtotime($item->listdate);
  // echo ">>$d".PHP_EOL;
	$db = connect_db();
	$query=mysqli_query($db,"REPLACE INTO phishing (url,ip,target_brand,listdate) VALUES ('$item->url' , '$item->ip','$item->target_brand',from_unixtime($d));");
  // $query=mysqli_query($db,"REPLACE INTO phishing (url,ip,target_brand,listdate) VALUES ('$item->url' , '$item->ip','$item->target_brand',now());");
	$db->close();
}

function log_botnet($item){
	$db = connect_db();
	$query=mysqli_query($db,"REPLACE INTO botnet (title,link,description,guid) VALUES ('$item->title' , '$item->link','$item->description','$item->guid');");
	$db->close();
}

function log_spam($item){
  $d = strtotime($item->latest_activity);
	$db = connect_db();
	$query=mysqli_query($db,"REPLACE INTO spam (ip,host,country,latest_type_threat,total_website,total_browser,latest_activity) VALUES ('$item->ip' , '$item->host','$item->country','$item->latest_type_threat','$item->total_website','$item->total_browser',from_unixtime($d));");
	$db->close();
}

function log_malmware($item){
  $d = strtotime($item->listdate);
	$db = connect_db();
	$query=mysqli_query($db,"REPLACE INTO malmware (domain,ip,r_lookup,description,registrant,asn,country,listdate) VALUES ('$item->domain' , '$item->ip','$item->reverse_lookup','$item->description','$item->registrant','$item->asn','$item->country',from_unixtime($d));");
	$db->close();
}

// FUNCTION TO GET THE CLIENT IP ADDRESS
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
      case 2:// DEFACEMENT
          $r = new stdClass();
          $r = json_decode($result);
          $r = new DefacementObj02(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_defacement($mydata);
          }

          break;
      case 8:// MALMWARE
          $r = new stdClass();
          $r = json_decode($result);
          $r = new MalmwareObj08(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_malmware($mydata);
          }

          break;
      case 9:// BOTNET
          $r = new stdClass();
          $r = json_decode($result);
          $r = new BotnetObj09(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_botnet($mydata);
              // echo($mydata->link.PHP_EOL);
          }

          break;
      case 10:// BOTNET
        	$r = new stdClass();
        	$r = json_decode($result);
          $r = new BotnetObj10(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_botnet($mydata);
          }

          break;
      case 13:// PHISHING
          $r = new stdClass();
          $r = json_decode($result);
          $r = new PhishingObj13(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_phishing($mydata);
          }

          break;
      case 14:// SPAM
          $r = new stdClass();
          $r = json_decode($result);
          $r = new SpamObj14(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_spam($mydata);
          }
          break;
      default:
          echo($result);
    }
}

// DEFACEMENT 2
class DefacementObj02 {
    public function __construct($obj) {
        $rs = array();
        $c1 = 0;
        foreach($obj->query->results->table->tbody->tr AS $mydata)
        {
            // if(($c1>1)&&($c1<=101)){
                $c=0;
                $r = new stdClass();
                foreach($mydata->td AS $mymy){
                  switch ($c) {
                    case 0:
                        // $r->url = implode(" ", explode("_",$mymy->nobr));
                        $r->listdate = $mymy;
                        break;
                    case 1:
                        // $r->url = implode(" ", explode("_",$mymy->nobr));
                        $r->attacker = $mymy->a->content;
                        break;
                    case 2:
                        // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        $r->team = $mymy->a->content;
                        break;
                    case 3:
                        $r->homepage_deface = $mymy->content;
                        break;
                    case 4:
                        $r->mass_deface = $mymy->content;
                        break;
                    case 5:
                        $r->re_deface = $mymy->content;
                        break;
                    case 6:
                        $r->location = $mymy->img->title;
                        break;
                    case 7:
                        $r->special_deface = $mymy->content;
                        break;
                    case 8:
                        $r->domain = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 9:
                        $r->os = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    default:
                        // do nothing;
                  }
                  $c++;
                }
                array_push($rs, $r);
            // }
            $c1++;
        }

        $this->query->count = count($rs);
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        // $this->query->results->item = $obj->query->results->table->tbody->tr;
        $this->query->results->item = $rs;
    }
}

// PHISHING 13
class PhishingObj13 {
    public function __construct($obj) {
        $rs = array();
        $c1 = 0;
        foreach($obj->query->results->table->tbody->tr AS $mydata)
        {
            if(($c1>1)&&($c1<=101)){
                $c=0;
                $r = new stdClass();
                foreach($mydata->td AS $mymy){
                  switch ($c) {
                    case 1:
                        // $r->url = implode(" ", explode("_",$mymy->nobr));
                        $r->url = $mymy->a->content;
                        break;
                    case 2:
                        // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        $r->ip = $mymy->a->content;
                        break;
                    case 3:
                        $r->target_brand = null;//($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 4:
                        $r->listdate = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    default:
                        // do nothing;
                  }
                  $c++;
                }
                array_push($rs, $r);
            }
            $c1++;
        }

        $this->query->count = count($rs);
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        // $this->query->results->item = $obj->query->results->table->tbody->tr;
        $this->query->results->item = $rs;
    }
}

// MALMWARE 8
class MalmwareObj08 {
    public function __construct($obj) {
        $rs = array();
        foreach($obj->query->results->table->tbody->tr AS $mydata)
        {
            if($mydata->class!="tabletitle"){
                $c=0;
                $r = new stdClass();
                foreach($mydata->td AS $mymy){
                  switch ($c) {
                    case 0:
                        $r->listdate = implode(" ", explode("_",$mymy->nobr));
                        break;
                    case 1:
                        $r->domain = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 2:
                        $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 3:
                        $r->reverse_lookup = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 4:
                        $r->description = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 5:
                        $r->registrant = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 6:
                        $r->asn = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 7:
                        $r->country = $mymy->img->title;
                        break;
                    default:
                        // do nothing;
                  }
                  $c++;
                }
                array_push($rs, $r);
            }
        }
        $this->query->count = count($rs);
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        // $this->query->results->item = $obj->query->results->table->tbody->tr;
        $this->query->results->item = $rs;
    }
}

// SPAM 14
class SpamObj14 {
    public function __construct($obj) {
        $rs = array();
        foreach($obj->query->results->table->tbody->tr AS $mydata)
        {
            unset($mydata->class);
            // $mydata->tdcount = count($mydata->td);
            if(count($mydata->td)==8){
                $c = 0;
                $r = new stdClass();
                foreach($mydata->td AS $mymy)
                {
                    switch ($c) {
                      case 1:
                          $r->ip = $mymy->a->content;
                          break;
                      case 2:
                          $r->host = $mymy->content;
                          break;
                      case 3:
                          $r->country = $mymy->a->content;
                          break;
                      case 4:
                          $r->latest_type_threat = $mymy->a->content;
                          break;
                      case 5:
                          $r->total_website = $mymy->a->content;
                          break;
                      case 6:
                          $r->total_browser = $mymy->a->content;
                          break;
                      case 7:
                          $r->latest_activity = $mymy->content;
                          break;
                      default:
                          // do nothing;
                    }
                    $c++;
                    unset($mymy->class);
                    unset($mymy->align);
                    unset($mymy->img);
                    unset($mymy->br);

                }
                array_push($rs, $r);
            }else{
                unset($mydata);
            }
        }

        $this->query->count = count($rs);
        $this->query->created = $obj->query->created;
        $this->query->lang = $obj->query->lang;
        // $this->query->results->item = $obj->query->results->table;
        $this->query->results->item = $rs;
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
