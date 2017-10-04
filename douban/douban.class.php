<?php
#############################################################################
# DoubanMovie                                                               #
# written by wqdonald                                                       #
# this code is referred to IMDBPHP of Giorgos Giagas & Itzchak Rehberg      #
#############################################################################

/* $Id: douban_config.php,v1.0 2017/10/03 18:15:45 wqdonald $ */

require_once ("include/browser/browseremulator.class.php");
require_once ("include/browser/info_extractor.php");
require_once (dirname(__FILE__)."/douban_config.php");

/** Accessing Douban Movie information
* @package Api
* @class douban
* @extends douban_config
* @author wqdonald (www.wqdonald.cc)
* @version $Revision: 1.0 $ $Date: 2017/10/03 18:15:45 $
*/
class douban extends douban_config {
  var $doubanID = "";
  var $page;

  var $main_title = "";
  var $main_original_title = "";
  var $main_aka = "";
  var $main_akas = "";
  var $main_year = "";
  var $main_director = "";
  var $main_directors = "";
  var $main_cast = "";
  var $main_casts = "";
  var $main_rating = "";
  var $main_votes = "";
  var $main_genre = "";
  var $main_genres = "";
  var $main_country = "";
  var $main_countries = "";
  var $main_image = "";
  var $main_summary = "";
  var $main_doubanlink = "";

  var $info_excer;

  var $extension = array('Title');

  // for debug
  function debug_scalar($scalar) {
    echo "<b><font color='#ff0000'>$scalar</font></b><br>";
  }
  function debug_object($object) {
    echo "<font color='#ff0000'><pre>";print_r($object);echo "</pre></font>";
  }
  function debug_html($html) {
    echo "<b><font color='#ff0000'>".htmlentities($html)."</font></b><br>";
  }

  /** Check doubanID
  * @method urlstate ()
  * @param none
  * @return int state (0-not valid, 1-valid)
  */
  function urlstate () {
    if (strlen($this->doubanID) != 7) return 0;
    else return 1;
  }

  /** Check Douban movie cache state
  * @method cachestate ()
  * @param $target array
  * @return int state (0-not complete, 1-cache complete, 2-cache not enabled, 3-not valid imdb url)
  */
  function cachestate ($target = "") {
    if (strlen($this->doubanID) != 7) {
      //echo "Invalid doubanID: ".$this->doubanID."<BR>".strlen($this->doubanID);
      $this->page[$wt] = "cannot open page";
      return 3;
    }
    if ($this->usecache) {
      $ext_arr =	$this->extension;
      foreach($ext_arr as $ext) {
        if(!file_exists($this->cachedir."/".$this->doubanID.$ext)) return 0;
        @$fp = fopen($this->cachedir."/".$this->doubanID.$ext, "r");
        if (!$fp) return 0;
      }
      return 1;
    }
    else return 2;
  }

  /** Prepare for movie cache
  * @method preparecache
  * @param $target array
  */
  function preparecache ($target = "") {
    $ext_arr = $this->extension;
    foreach($ext_arr as $ext) {
      $tar_ext = array($ext);
      if($this->cachestate($tar_ext) == 0) $this->openpage($ext);
    }
    return $ext;
  }

  /** Open Douban movie page
  * @method openpage
  * @param string wt
  */
  function openpage ($wt) {
    if (strlen($this->doubanID) != 7) {
      echo "Invalid doubanID: ".$this->doubanID."<BR>".strlen($this->doubanID);
      $this->page[$wt] = "cannot open page";
      return;
    }
    switch ($wt) {
      //case "Title"	 : $urlname="/"; return FALSE;
      // case "Credits" : $urlname="/fullcredits"; return FALSE;
      //case "Plot"		: $urlname="/plotsummary"; return FALSE;
      //case "Taglines": $urlname="/taglines"; return FALSE;
      case "Title"	 : $urlname=""; return FALSE;
      //case "Credits" : $urlname=""; return FALSE;
      //case "Plot"		: $urlname=""; return FALSE;
      // case "Taglines": $urlname=""; return FALSE;
    }
    if ($this->usecache) {
      @$fp = fopen ($this->cachedir."/".$this->doubanID.$wt, "r");
      if ($fp) {
        $temp="";
        while (!feof ($fp)) {
          $temp .= fread ($fp, 1024);
        }
        if ($temp) {
          $this->page[$wt] = $temp;
          return;
        }
      }
    } // end cache

    // $req = new DOUBAN_Request("");
    // $req->setURL($this->main_url()."?apikey=".$this->apikey);
    // $response = $req->send();




    $url = $this->main_url();//url为当前Douban完整连接
    $curl = curl_init();//初始化一个CURL会话，cURL库可以简单和有效地去抓网页
    curl_setopt($curl, CURLOPT_URL,$url);//为CURL会话设置参数，CURLOPT_URL为$url
    curl_setopt($curl, CURLOPT_HEADER, 0);//CURLOPT_HEADER$url
    curl_setopt($curl, CURLOPT_NOBODY, 0);//CURLOPT_NOBODY$url
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//CURLOPT_RETURNTRANSFER$url
    curl_setopt($curl, CURLOPT_TIMEOUT,10);//CURLOPT_TIMEOUT$url
    $data = curl_exec($curl);//执行CURL会话
    curl_close($curl);//关闭CURL会话

    $this->page[$wt] = $data;

    /*
    if ($responseBody) {
    // $this->page[$wt] = utf8_encode($responseBody);
    //$this->page[$wt] = iconv ("gb2312","UTF-8",$responseBody);
  }
  */

  if($this->page[$wt]) {//如果成功获取到网页
    if ($this->storecache) {
      $fp = fopen ($this->cachedir."/".$this->doubanID.$wt, "w");//以写入方式打开文件
      fputs ($fp, $this->page[$wt]);//写入文件
      fclose ($fp);//关闭打开的文件
    }
    return;
  }
  $this->page[$wt] = "cannot open page";
  //echo "page not found";
}

/** Retrieve the Douban ID
* @method doubanID
* @return string id
*/
function doubanid () {
  return $this->doubanID;
}

/** Set a new doubanID
* @method setid
* @param string id
*/
function setid ($id) {
  $this->doubanID = $id;

  $this->page["Title"] = "";
  /*
  $this->page["Credits"] = "";
  $this->page["Amazon"] = "";
  $this->page["Goofs"] = "";
  $this->page["Plot"] = "";
  $this->page["Comments"] = "";
  $this->page["Quotes"] = "";
  $this->page["Taglines"] = "";
  $this->page["Plotoutline"] = "";
  $this->page["Trivia"] = "";
  $this->page["Directed"] = "";*/

  $this->main_title = "";
  $this->main_original_title = "";
  $this->main_aka = "";
  $this->main_akas = "";
  $this->main_year = "";
  $this->main_director = "";
  $this->main_directors = "";
  $this->main_cast = "";
  $this->main_casts = "";
  $this->main_rating = "";
  $this->main_votes = "";
  $this->main_genre = "";
  $this->main_genres = "";
  $this->main_country = "";
  $this->main_countries = "";
  $this->main_image = "";
  $this->main_summary = "";
  $this->main_doubanlink = "";

  $this->info_excer = new info_extractor();
}

/** Initialize class
* @constructor douban
* @param string id
*/
function douabn ($id) {
  $this->douban_config();
  $this->setid($id);
  //if ($this->storecache && ($this->cache_expire > 0)) $this->purge();
}

/** Check cache and purge outdated files
*  This method looks for files older than the cache_expire set in the
*  douban_config and removes them
* @method purge
*/
function purge($explicit = false) {
  if (is_dir($this->cachedir)) {
    $thisdir = dir($this->cachedir);
    $now = time();
    while( $file=$thisdir->read() ) {
      if ($file!="." && $file!="..") {
        $fname = $this->cachedir ."/". $file;
        if (is_dir($fname)) continue;
        $mod = filemtime($fname);
        if ($mod && (($now - $mod > $this->cache_expire) || $explicit == true)) unlink($fname);
      }
    }
  }
}

/** Check cache and purge outdated single douban title file
*  This method looks for files older than the cache_expire set in the
*  douban_config and removes them
* @method purge
*/
function purge_single($explicit = false) {
  if (is_dir($this->cachedir)) {
    $thisdir = dir($this->cachedir);
    foreach($this->extension as $ext) {
      $fname = $this->cachedir ."/". $this->doubanid() . "." . $ext;
      //return $fname;
      if(file_exists($fname)) {
        $now = time();
        $mod = filemtime($fname);
        if ($mod && (($now - $mod > $this->cache_expire) || $explicit == true)) unlink($fname);
      }
    }
  }
}

/** get the time that cache is stored
* @method getcachetime
*/
function getcachetime() {
  $mod =0;
  if (is_dir($this->cachedir)) {
    $thisdir = dir($this->cachedir);
    foreach($this->extension as $ext) {
      $fname = $this->cachedir ."/". $this->doubanid() . "." . $ext;
      if(file_exists($fname)) {
        if($mod > filemtime($fname) || $mod==0)
        $mod = filemtime($fname);
      }
    }
  }
  return $mod;
}

/** Set up the URL to the movie title page
* @method main_url
* @return string url
*/
function main_url(){
  return $this->protocol_prefix.$this->doubansite."/".$this->doubanid()."/";
}

/** Get title
* @method title
* @return string movie title
*/
function title() {
  if ($this->main_title == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "title";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $this->main_title = $data[$jkey];
  }
  return $this->main_title;
}
/** Get original title
* @method original_title
* @return string original title
*/
function original_title() {
  if ($this->main_original_title == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "original_title";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $this->main_original_title = $data[$jkey];
  }
  return $this->main_original_title;
}

/** Get movie aka name
* @method aka
* @return string aka name
*/
function aka() {
  if ($this->main_aka == "") {
    if ($this->main_akas == "" || count($this->main_akas) <= 0) {
      $this->openpage("Title");
      $data = json_decode($this->page["Title"],true);
      $jkey = "aka";
      if (!array_key_exists($jkey, $data)) {
        echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
        return FALSE;
      }
      $akas_info = $data[$jkey];
      $aka_count = count($akas_info);
      foreach ($akas_info as $aka_info) {
        if ($this->main_akas == "") $this->main_akas = array();
        array_push($this->main_akas, $aka_info);
      }
    }
    foreach ($this->main_akas as $aka_name) {
      if ($this->main_aka == "") $this->main_aka = $aka_name;
      else $this->main_aka = $this->main_aka." / ".$aka_name;
    }
  }
  return $this->main_aka;
}

/** Get public year
* @method year
* @return string public year
*/
function year() {
  if ($this->main_year == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "year";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $this->main_year = $data[$jkey];
  }
  return $this->main_year;
}

/** Get director
* @method director
* @return string director
*/
function director() {
  if ($this->main_director == "") {
    if ($this->main_directors == "" || count($this->main_directors) <= 0) {
      $this->openpage("Title");
      $data = json_decode($this->page["Title"],true);
      $jkey = "directors";
      if (!array_key_exists($jkey, $data)) {
        echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
        return FALSE;
      }
      $directors_info = $data[$jkey];
      foreach ($directors_info as $director_info) {
        if (!array_key_exists("name",$director_info)) {
          echo "<br /> JSON Parser Error: json key 'name' does not exist in '".$jkey."' value. <br />";
          return FALSE;
        }
        if ($this->main_directors == "") $this->main_directors = array();
        array_push($this->main_directors, $director_info["name"]);
      }
    }
    foreach ($this->main_directors as $director_name) {
      if ($this->main_director == "") $this->main_director = $director_name;
      else $this->main_director = $this->main_director." / ".$director_name;
    }
  }
  return $this->main_director;
}

/** Get casts
* @method cast
* @return string casts
*/
function cast() {
  if ($this->main_cast == "") {
    if ($this->main_casts == "" || count($this->main_casts) <= 0) {
      $this->openpage("Title");
      $data = json_decode($this->page["Title"],true);
      $jkey = "casts";
      if (!array_key_exists($jkey, $data)) {
        echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
        return FALSE;
      }
      $casts_info = $data[$jkey];
      foreach ($casts_info as $cast_info) {
        if (!array_key_exists("name",$cast_info)) {
          echo "<br /> JSON Parser Error: json key 'name' does not exist in '".$jkey."' value. <br />";
          return FALSE;
        }
        if ($this->main_casts == "") $this->main_casts = array();
        array_push($this->main_casts, $cast_info["name"]);
      }
    }
    foreach ($this->main_casts as $cast_name) {
      if ($this->main_cast == "") $this->main_cats = $cast_name;
      else $this->main_cast = $this->main_cast." / ".$cast_name;
    }
  }
  return $this->main_cast;
}

/** Get rating
* @method rating
* @return string rating
*/
function rating() {
  if ($this->main_rating == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "rating";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $rating_info = $data[$jkey];
    if(!array_key_exists("average",$rating_info)) {
      echo "<br /> JSON Parser Error: json key 'average' does not exist in '".$jkey."' value. <br />";
      return FALSE;
    }
    $this->main_rating = $rating_info["average"];
  }
  return $this->main_rating;
}

/** Get votes count
* @method votes
* @return string vote count
*/
function votes() {
  if ($this->main_votes == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "ratings_count";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $this->main_votes = $data[$jkey];
  }
  return $this->main_votes;
}

/** Get genres
* @method genre
* @return string genre
*/
function genre() {
  if ($this->main_genre == "") {
    if ($this->main_genres == "" || count($this->main_genres) <= 0) {
      $this->openpage("Title");
      $data = json_decode($this->page["Title"],true);
      $jkey = "genres";
      if (!array_key_exists($jkey, $data)) {
        echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
        return FALSE;
      }
      $genres_info = $data[$jkey];
      foreach ($genres_info as $genre_info) {
        if ($this->main_genres == "") $this->main_genres = array();
        array_push($this->main_genres, $genre_info);
      }
    }
    foreach ($this->main_genres as $genre_name) {
      if ($this->main_genre == "") $this->main_genre = $genre_name;
      else $this->main_genre = $this->main_genre." / ".$genre_name;
    }
  }
  return $this->main_genre;
}

/** Get country
* @method country
* @return string country
*/
function country() {
  if ($this->main_country == "") {
    if ($this->main_countries == "" || count($this->main_countries) <= 0) {
      $this->openpage("Title");
      $data = json_decode($this->page["Title"],true);
      $jkey = "countries";
      if (!array_key_exists($jkey, $data)) {
        echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
        return FALSE;
      }
      $countries_info = $data[$jkey];
      foreach ($countries_info as $country_info) {
        if ($this->main_countries == "") $this->main_countries = array();
        array_push($this->main_countries, $country_info);
      }
    }
    foreach ($this->main_countries as $country_name) {
      if ($this->main_country == "") $this->main_country = $country_name;
      else $this->main_country = $this->main_country." / ".$country_name;
    }
  }
  return $this->main_country;
}

/** Get image file
* @method image
* @return string or boolean image stream
*/
function image() {
  if ($this->main_image == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "images";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $image_urls = $data[$jkey];
    if (array_key_exists("medium", $image_urls)) {
      $url = $image_urls["medium"];
      $this->main_image = stripslashes($url);
    }
    else if (array_key_exists("large", $image_urls)) {
      $url = $image_urls["large"];
      $this->main_image = stripslashes($url);
    }
    else if (array_key_exists("small", $image_urls)) {
      $url = $image_urls["small"];
      $this->main_image = stripslashes($url);
    }
    else {
      echo "<br /> JSON Parser Error: these is not porper image source. <br />";
      return FALSE;
    }
  }
  return $this->main_image;
}

/** Get image Local url
* @method image_url
* @return string image url
*/
function image_url() {
  $path = $this->photodir."/".$this->doubanID.".jpg";
  if(fopen($this->main_imageurl,"r")) return $path;
  if ($this->savephoto($path)) return $path;
  return FALSE;
}

/** Get summary
* @method summary
* @return string movie summary
*/
function summary() {
  if ($this->main_summary == "") {
    $this->openpage("Title");
    $data = json_decode($this->page["Title"],true);
    $jkey = "summary";
    if (!array_key_exists($jkey, $data)) {
      echo "<br /> JSON Parser Error: json key '".$jkey."' does not exist. <br />";
      return FALSE;
    }
    $this->main_summary = $data[$jkey];
  }
  return $this->main_summary;
}

/** Get doubanlink
* @method doubanlink
* @return string doubanlink url
*/
function doubanlink() {
  if ($this->main_doubanlink == "") {
    $this->main_doubanlink = "https://".$this->details_url."/".$this->doubanID."/";
  }
  return $this->main_doubanlink;
}

/** Save the photo to disk
* @method savephoto
* @param string path
* @return boolean success
*/
function savephoto ($path) {
  $req = new DOUBAN_Request("");
  $photo_url = $this->image();
  if (!$photo_url) return FALSE;
  $req->setUrl($photo_url);
  $response = $req->send();
  if (strpos($response->getHeader("Content-Type"),'image/jpeg') === 0
  || strpos($response->getHeader("Content-Type"),'image/gif') === 0
  || strpos($response->getHeader("Content-Type"), 'image/bmp') === 0 ){
    $fp = $response->getBody();
  }else{
    echo "<BR>*photoerror* ".$photo_url.": Content Type is '".$req->getResponseHeader("Content-Type")."'<BR>";
    return FALSE;
  }

  $fp2 = fopen ($path, "w");
  if ((!$fp) || (!$fp2)){
    echo "image error...<BR>";
    return false;
  }

  fputs ($fp2, $fp);
  fclose($fp2);
  return TRUE;
}
}

?>
