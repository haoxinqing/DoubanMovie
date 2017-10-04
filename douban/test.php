<?php
require_once("douban.class.php");

if(isset($_GET)) {
  if(!isset($_GET["doubanID"])) {
    echo "<br />Invalid Request. <br />";
    exit;
  }
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
    <form method="get" action="test.php">
    DoubanID: <input type="text" name="doubanID" />
    <br /><br />
    <input type="submit" value="提交" />
    </form>
EOT;
  }
  else {
    echo '<br /> <br />';
    echo '<h3>豆瓣电影 '.$doubanId." 详情</h3><br />";

    if (isset($movie_title) && $movie_title != "" && $movie_title != FALSE) {
      echo '<h3>'.$movie_title.'</h3><br />';
    }
    echo '<br /><br />';
    if (isset($movie_image) && $movie_image != "" && $movie_image != FALSE) {
      echo '<img src="'.$movie_image.'" />';
    }
    echo '<br /><br />';
    echo '<table width="60%">';
    if (isset($movie_original_title) && $movie_original_title != "" && $movie_original_title != FALSE) {
      echo '<tr><td>原  名：</td><td>'.$movie_original_title.'</td></tr>';
    }
    if (isset($movie_aka) && $movie_aka != "" && $movie_aka != FALSE) {
      echo '<tr><td>又  名：</td><td>'.$movie_aka.'</td></tr>';
    }
    if (isset($movie_year) && $movie_year != "" && $movie_year != FALSE) {
      echo '<tr><td>上映年份：</td><td>'.$movie_year.'</td></tr>';
    }
    if (isset($movie_director) && $movie_director != "" && $movie_director != FALSE) {
      echo '<tr><td>导  演：</td><td>'.$movie_director.'</td></tr>';
    }
    if (isset($movie_cast) && $movie_cast != "" && $movie_cast != FALSE) {
      echo '<tr><td>主  演：</td><td>'.$movie_cast.'</td></tr>';
    }
    if (isset($movie_rating) && $movie_rating != "" && $movie_rating != FALSE) {
      echo '<tr><td>评  分：</td><td>'.$movie_rating.'</td></tr>';
    }
    if (isset($movie_votes) && $movie_votes != "" && $movie_votes != FALSE) {
      echo '<tr><td>参评人数：</td><td>'.$movie_votes.'</td></tr>';
    }
    if (isset($movie_genre) && $movie_genre != "" && $movie_genre != FALSE) {
      echo '<tr><td>类  型：</td><td>'.$movie_genre.'</td></tr>';
    }
    if (isset($movie_country) && $movie_country != "" && $movie_country != FALSE) {
      echo '<tr><td>制片国家：</td><td>'.$movie_country.'</td></tr>';
    }
    if (isset($movie_doubanlink) && $movie_doubanlink != "" && $movie_doubanlink != FALSE) {
      echo '<tr><td>豆瓣链接：</td><td>'.$movie_doubanlink.'</td></tr>';
    }
    if (isset($movie_summary) && $movie_summary != "" && $movie_summary != FALSE) {
      echo '<tr><td>剧情简介：</td><td>'.$movie_summary.'</td></tr>';
    }
    echo '</table>';
  }

  ?>
</body>

</html>
