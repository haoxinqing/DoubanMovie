# DoubanMovie
Douban Movie Basic Information Extractor

this is a douban movie basic information extractor code, which is refer to [IMDBPHP v1.13](http://www.qumran.org/homes/izzy/) , using [douban movie API v2](https://developers.douban.com/wiki/?title=movie_v2) (movie_basic_r), cURL and HTTP_Request2.

# Movie Information
accessible information now
- title.
- original_title.
- aka.
- year.
- director.
- casts.
- rating.
- votes.
- genres.
- countries.
- summary.
- image.
- doubanlink.

# Usage Demo

```php
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
```
you can find demo use details in test.php
