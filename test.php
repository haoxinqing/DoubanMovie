<?php
require_once("douban/douban.class.php");

if(isset($_GET)) {
  if(isset($_GET["doubanID"])) {
    $doubanId = $_GET["doubanID"];

    $instance = new douban($doubanId);
    $movie_title = $instance->title();
    $movie_original_title = $instance->original_title();
    $movie_aka = $instance->aka();
    $movie_year = $instance->year();
    $movie_director = $instance->director();
    $movie_cast = $instance->cast();
    $movie_rating = $instance->rating();
    $movie_votes = $instance->votes();
    $movie_genre = $instance->genre();
    $movie_country = $instance->country();
    $movie_summary = $instance->summary();
    $movie_image = $instance->image_url();
    $movie_doubanlink = $instance->doubanlink();
    $movie_wishnum = $instance->wishes();
    $movie_collectnum = $instance->collect();
    $movie_mobileurl = $instance->mobileurl();
    $movie_subtype = $instance->subtype();

    $movie_writer = $instance->writer();
    $movie_pubdate = $instance->pubdate();
    $movie_language = $instance->language();
    $movie_duration = $instance->durations();
    $movie_imdbid = $instance->imdbid();
  }
}

?>

<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>豆瓣电影解析</title>
</head>

<body>

  <?php
  if (!$_GET || !isset($_GET["doubanID"])) {
    print <<< EOT
    <img src="images/douban_tag.png" alt="www.douban.com" />&nbsp;<h3>豆瓣电影基本信息解析</h3>
    <br />
    <form method="get" action="test.php">
    DoubanID: <input type="text" name="doubanID" value="26634179" />&nbsp;&nbsp;<input type="submit" value="提交" />
    </form>
EOT;
  }
  else {
    echo '<br /> <br />';
    echo '<h3>豆瓣电影 '.$doubanId." 详情</h3><br />";

    if (isset($movie_title) && $movie_title != "" && $movie_title != FALSE) {
      echo '<h3>'.$movie_title.'</h3><br />';
      echo '<br />';
    }
    if (isset($movie_image) && $movie_image != "" && $movie_image != FALSE) {
      echo '<img src="'.$movie_image.'" />';
      echo '<br />';
    }
    else {
      $default_img = "images/nophoto.gif";
      echo '<img src="'.$default_img.'" />';
      echo '<br />';
    }
    echo '<table width="60%">';
    if (isset($movie_original_title) && $movie_original_title != "" && $movie_original_title != FALSE) {
      echo '<tr><td width="15%">原  名：</td><td>'.$movie_original_title.'</td></tr>';
    }
    if (isset($movie_aka) && $movie_aka != "" && $movie_aka != FALSE) {
      echo '<tr><td width="15%">又  名：</td><td>'.$movie_aka.'</td></tr>';
    }
    if (isset($movie_year) && $movie_year != "" && $movie_year != FALSE) {
      echo '<tr><td width="15%">上映年份：</td><td>'.$movie_year.'</td></tr>';
    }
    if (isset($movie_director) && $movie_director != "" && $movie_director != FALSE) {
      echo '<tr><td width="15%">导  演：</td><td>'.$movie_director.'</td></tr>';
    }
    if (isset($movie_writer) && $movie_writer != "" && $movie_writer != FALSE) {
      echo '<tr><td width="15%">编  剧：</td><td>'.$movie_writer.'</td></tr>';
    }
    if (isset($movie_cast) && $movie_cast != "" && $movie_cast != FALSE) {
      echo '<tr><td width="15%">主  演：</td><td>'.$movie_cast.'</td></tr>';
    }
    if (isset($movie_rating) && $movie_rating != "" && $movie_rating != FALSE) {
      $rating_range = ((int)$movie_rating)*10;
      echo '<tr><td width="15%">评  分：</td><td>'.$movie_rating.'&nbsp;&nbsp;<img src="images/'.$rating_range.'.gif" /></td></tr>';
    }
    if (isset($movie_votes) && $movie_votes != "" && $movie_votes != FALSE) {
      echo '<tr><td width="15%">参评人数：</td><td>'.$movie_votes.'</td></tr>';
    }
    if (isset($movie_wishnum) && $movie_wishnum != "" && $movie_wishnum != FALSE) {
      echo '<tr><td width="15%">想看人数：</td><td>'.$movie_wishnum.'</td></tr>';
    }
    if (isset($movie_collectnum) && $movie_collectnum != "" && $movie_collectnum != FALSE) {
      echo '<tr><td width="15%">看过人数：</td><td>'.$movie_collectnum.'</td></tr>';
    }
    if (isset($movie_genre) && $movie_genre != "" && $movie_genre != FALSE) {
      echo '<tr><td width="15%">类  型：</td><td>'.$movie_genre.'</td></tr>';
    }
    if (isset($movie_subtype) && $movie_subtype != "" && $movie_subtype != FALSE) {
      echo '<tr><td width="15%">分  类：</td><td>'.$movie_subtype.'</td></tr>';
    }
    if (isset($movie_duration) && $movie_duration != "" && $movie_duration != FALSE) {
      echo '<tr><td width="15%">片  长：</td><td>'.$movie_duration.'</td></tr>';
    }
    if (isset($movie_language) && $movie_language != "" && $movie_language != FALSE) {
      echo '<tr><td width="15%">语  言：</td><td>'.$movie_language.'</td></tr>';
    }
    if (isset($movie_country) && $movie_country != "" && $movie_country != FALSE) {
      echo '<tr><td width="15%">制片国家：</td><td>'.$movie_country.'</td></tr>';
    }
    if (isset($movie_doubanlink) && $movie_doubanlink != "" && $movie_doubanlink != FALSE) {
      echo '<tr><td width="15%">豆瓣链接：</td><td>'.$movie_doubanlink.'</td></tr>';
    }
    if (isset($movie_mobileurl) && $movie_mobileurl != "" && $movie_mobileurl != FALSE) {
      echo '<tr><td width="15%">移动链接：</td><td>'.$movie_mobileurl.'</td></tr>';
    }
    if (isset($movie_imdbid) && $movie_imdbid != "" && $movie_imdbid != FALSE) {
      echo '<tr><td width="15%">IMDb链接：</td><td>http://www.imdb.com/title/tt'.$movie_imdbid.'</td></tr>';
    }
    if (isset($movie_summary) && $movie_summary != "" && $movie_summary != FALSE) {
      echo '<tr><td width="15%">剧情简介：</td><td>'.$movie_summary.'</td></tr>';
    }
    echo '</table>';
  }

  ?>
</body>

</html>
