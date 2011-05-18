<?php

require_once('twitterOAuth.php');
include 'config.inc';

require_once('Entry.class.php');
require_once('Author.class.php');

if (count($argv) != 2) {
  printf("usage: %s <hashtag>\n", $argv[0]);
  exit;
}

$hashtag = $argv[1];
if ('#' !== $hashtag[0]) $hashtag = '#'.$hashtag;



$my_user_id = '198824125';
$ignore_ids = array('198824125' => true); // myself


function http_post($url, $data, $content_type='text/html')
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, Array('Content-Type: '.$content_type));
#  curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode($data));
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
# curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($ch);
  curl_close($ch);

  return $response;
}

//
//
//

$to = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret);

$params = array();

#$params['q'] = urlencode($hashtag);
$params['q'] = $hashtag;
$params['rpp'] = 100;

$since_id = Entry::maxId();
if ($since_id)
{
  $params['since_id'] = $since_id;
}
else
{
# $params['since'] = '2011-05-05';
}

$response = $to->oAuthRequest('http://search.twitter.com/search.atom', 'GET', $params);
$xml = simplexml_load_string($response);

foreach ($xml->entry as $entry)
{
  preg_match('/([^ ]+) \(([^)]+)\)/', $entry->author->name, $matches);

  $screen_name = $matches[1];
  $name = $matches[2];
  $tweet_id = preg_replace('/^tag:.*:/', '', $entry->id); 
  

  $target = sprintf("%s (%s) <%s> %s: %s",
		  $name, $screen_name,
                  $tweet_id, $entry->published,
		  $entry->title );
  echo $target.PHP_EOL;


  $theAuthor = new Author($screen_name,
                          $name,
                          ''.$entry->author->uri,
                          ''.$entry->link[1]['href']);
  $theAuthor->save();


  $theEntry = new Entry($tweet_id,
                        ''.$entry->published,
                        ''.$entry->title,
                        ''.$entry->content,
                        $screen_name,
                        ''.$entry->link[0]['href']);
  $theEntry->save();
}

