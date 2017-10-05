<?php
#############################################################################
# DoubanMovie                                                               #
# written by wqdonald                                                       #
# this code is referred to IMDBPHP of Giorgos Giagas & Itzchak Rehberg      #
#############################################################################

/* $Id: douban_config.php,v1.0 2017/10/03 18:10:23 wqdonald $ */

// the proxy to use for connections to douban.
// leave it empty for no proxy.
// this is only supported with PEAR.
define ('PROXY', "");
define ('PROXY_PORT', "");

/** Configuration part of the DOUBAN classes
* @package Api
* @class douban_config
*/
class douban_config {
  var $protocol_prefix;
  var $doubansite;
  var $details_url;

  // OAuth parameters
  var $auth_url;
  var $apikey;
  var $secret;
  var $access_token;
  var $refresh_token;

  // cache parameters
  var $cachedir;
  var $usecache;
  var $storecache;
  var $cache_expire;
  var $photodir;
  var $photoroot;
  var $timeout;
  var $imageext;

  /** Constructor and only method of this base class.
  *  There's no need to call this yourself - you should just place your
  *  configuration data here.
  * @constructor douban_config
  */
  function douban_config(){
    // protocol prefix
    $this->protocol_prefix = "http://";
    // the douban server to use.
    // choices are douban api v2 (api.douban.com/v2/movie/subject/:id)
    $this->doubansite = "api.douban.com/v2/movie/subject";
    // movie details url (movie.douban.com/subject/:id)
    $this->details_url = "movie.douban.com/subject";
    // OAuth Url
    $this->auth_url = "www.douban.com/service/auth2/auth";
    // apikey
    $this->apikey = "";
    // secret
    $this->secret = "";
    // access_token
    $this->access_token = "";
    // refresh_token
    $this->refresh_token = "";
    // cachedir should be writable by the webserver. This doesn't need to be
    // under documentroot.
    $this->cachedir = './douban/cache';
    //whether to use a cached page to retrieve the information if available.
    $this->usecache = true;
    //whether to store the pages retrieved for later use.
    $this->storecache = true;
    // automatically delete cached files older than X secs
    $this->cache_expire = 365*24*60*60;
    // the extension of cached images
    $this->imageext = '.jpg';
    // images are stored here after calling photo_localurl()
    // this needs to be under documentroot to be able to display them on your pages.
    $this->photodir = './douban/images';
    // this is the URL to the images, i.e. start at your servers DOCUMENT_ROOT
    // when specifying absolute path
    $this->photoroot = './douban/images';
    // TWEAKING OPTIONS:
    // limit the result set to X movies (0 to disable, comment out to use default of 20)
    $this->maxresults = 5000;
    // timeout for retriving info, uint in second
    $this->timeout = 120;
    ////////// out dated time for retrived info, (7 days for default)
    $this->outdate_time = 60*60*24*30;
    // search variants. Valid options are "sevec" and "moonface". Comment out
    // (or set to empty string) to use the default
    $this->searchvariant = "";
  }

}

require_once ("HTTP/Request2.php");

class DOUBAN_Request extends HTTP_Request2
{
  function DOUBAN_Request($url){
    parent::__construct($url);
    if ( PROXY != ""){
      $this->setConfig(array('proxy_host' => PROXY, 'proxy_port' => PROXY_PORT));
    }
    $this->setConfig('follow_redirects', false);
    $this->setHeader("User-Agent", "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
  }
}
?>
