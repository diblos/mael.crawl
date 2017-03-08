<?php

define("ENV", "environment.json", true);
define("YAPI", "http://query.yahooapis.com/v1/public/yql", true);

define("CAT_DEFACEMENT", "defacement", true);
define("CAT_MALMWARE", "malmware", true);
define("CAT_BOTNET", "botnet", true);
define("CAT_PHISHING", "phishing", true);
define("CAT_SPAM", "spam", true);

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

function getEnvironments($url,$category)
{
    $tmp  = array();
    $contents = file_get_contents($url);
    $json =json_decode($contents);
    foreach ($json as $value){
      if($value->category==$category) array_push($tmp,$value);      
    }
    return $tmp;
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
      case 4:// MALMWARE
          $r = new stdClass();
          $r = json_decode($result);
          $r = new MalmwareObj04(json_decode($result));
          echo (json_encode($r));
          // foreach($r->query->results->item AS $mydata)
          // {
          //     log_malmware($mydata);
          // }
          break;
      case 5:// MALMWARE
          $r = new stdClass();
          $r = json_decode($result);
          $r = new MalmwareObj05(json_decode($result));

          echo (json_encode($r));
          // foreach($r->query->results->item AS $mydata)
          // {
          //     log_malmware($mydata);
          // }

          break;
      case 6:// MALMWARE
          $r = new stdClass();
          $r = json_decode($result);
          $r = new MalmwareObj06(json_decode($result));

          echo (json_encode($r));
          // foreach($r->query->results->item AS $mydata)
          // {
          //     log_malmware($mydata);
          // }

          break;
      case 7:// MALMWARE
          $r = new stdClass();
          $r = json_decode($result);
          $r = new MalmwareObj07(json_decode($result));

          echo (json_encode($r));
          // foreach($r->query->results->item AS $mydata)
          // {
          //     log_malmware($mydata);
          // }

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
      case 12:// PHISHING
          $r = new stdClass();
          $r = json_decode($result);
          $r = new PhishingObj12(json_decode($result));

          // echo (json_encode($r));
          foreach($r->query->results->item AS $mydata)
          {
              log_phishing($mydata);
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

// PHISHING 12
class PhishingObj12 {
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
                        $r->url = $mymy->a->content;
                        break;
                    // case 1:
                    //     // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                    //     $r->ip = $mymy->a->content;
                    //     break;
                    case 1:
                        $r->target_brand = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 2:
                        $r->listdate = gmdate("Y-m-d ") . $mymy;
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

// MALMWARE 4
class MalmwareObj04 {
    public function __construct($obj) {
        $rs = array();
        $c1 = 0;
        foreach($obj->query->results->table->tbody->tr AS $mydata)
        {
            if($c1>0){
                $c2=0;
                $r = new stdClass();
                foreach($mydata->td AS $mymy){
                  switch ($c2) {
                    case 0:
                        $r->listdate = $mymy;
                        break;
                    case 1:
                        $r->domain = ($mymy->content) ? $mymy->content : $mymy;
                        break;
                    case 2:
                        // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        $r->ip = $mymy->a->content;
                        break;
                    case 3:
                        // $r->country = ($mymy->content) ? $mymy->content : $mymy;
                        $r->country = $mymy->a->content;
                        break;
                    case 4:
                        // $r->asn = ($mymy->content) ? $mymy->content : $mymy;
                        $r->asn = $mymy->a->content;
                        break;
                    case 5:
                        // $r->AutonomousSystemName = ($mymy->content) ? $mymy->content : $mymy;
                        $r->AutonomousSystemName = $mymy->a->content;
                        break;
                    case 6:
                        // $r->md5 = ($mymy->content) ? $mymy->content : $mymy;
                        $r->md5 = $mymy->a->content;
                        break;
                    default:
                        // do nothing;
                  }
                  $c2++;
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

// MALMWARE 5
class MalmwareObj05 {
    public function __construct($obj) {
        $rs = array();
        $c1 = 0;
        foreach($obj->query->results->table->tbody->tr AS $mydata)
        {
            if($c1>0){
                $c2=0;
                $r = new stdClass();
                foreach($mydata->td AS $mymy){
                  switch ($c2) {
                    case 0:
                        $r->listdate = strftime(gmdate("Y")."-").$mymy->a->content;
                        break;
                    case 1:
                        // $r->domain = ($mymy->content) ? $mymy->content : $mymy;
                        $r->domain = $mymy->a[1]->content;
                        break;
                    case 2:
                        // $r->md5 = ($mymy->content) ? $mymy->content : $mymy;
                        $r->md5 = $mymy->a->content;
                        break;
                    case 3:
                        // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        $r->ip = $mymy->a->content;
                        break;
                    case 4:
                        // LINKS TO PEDUMP & URL QUERY
                        // $r->tool = ($mymy->content) ? $mymy->content : $mymy;
                        $r->tool->PED = $mymy->a[0]->href;
                        $r->tool->UQ = $mymy->a[1]->href;
                        break;
                    // case 5:
                    //     $r->registrant = ($mymy->content) ? $mymy->content : $mymy;
                    //     break;
                    // case 6:
                    //     $r->asn = ($mymy->content) ? $mymy->content : $mymy;
                    //     break;
                    // case 7:
                    //     $r->country = $mymy->img->title;
                    //     break;
                    default:
                        // do nothing;
                  }
                  $c2++;
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

// MALMWARE 6
class MalmwareObj06 {
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
                        $r->domain = $mymy->a->content;
                        break;
                    case 2:
                        // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        $r->ip = $mymy->a->content;
                        break;
                    case 3:
                        // $r->target_brand = ($mymy->content) ? $mymy->content : $mymy;
                        $r->class = $mymy->a->content;
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

// MALMWARE 7
class MalmwareObj07 {
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
                        $r->domain = $mymy->a->content;
                        break;
                    case 2:
                        // $r->ip = ($mymy->content) ? $mymy->content : $mymy;
                        $r->ip = $mymy->a->content;
                        break;
                    case 3:
                        // $r->target_brand = ($mymy->content) ? $mymy->content : $mymy;
                        $r->class = $mymy->a->content;
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
?>
