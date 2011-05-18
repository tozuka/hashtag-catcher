<?php
require_once('Database_pdo.class.php');

function end_of_month($year, $month)
{
  if ($month == 2) return ($year % 4) ? 28 : 29;  # only valid between 1901-2099
  else if ($month == 4 || $month == 6 || $month == 9 || $month == 11) return 30;
  else return 31;
}

function jst($gmt)
{
  $year  = substr($gmt, 0, 4);
  $month = substr($gmt, 5, 2);
  $day   = substr($gmt, 8, 2);
  $hh    = substr($gmt, 11, 2) + 9;
  $mm    = substr($gmt, 14, 2);
  $ss    = substr($gmt, 17, 2);

  if ($hh >= 24) {
    $hh -= 24;
    $day++;
    if ($day >= end_of_month($year, $month)) {
      $day = 1;
      $month++;
      if ($month == 13) {
        $month = 1;
        $year++;
      }
    }
  }

  if (2000 < $year && $year < 2100)
    return sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hh, $mm, $ss);
  return 0;
}

function pager($page, $last_page)
{
  $tag = '';
  if ($page > 1) $tag .= '<a href="index.php?page='.($page-1).'">Prev</a> |&nbsp;';

  for ($p=1; $p<=$last_page; ++$p)
  {
    if ($p == $page) $tag .= $p.'&nbsp;';
    else             $tag .= '<a href="index.php?page='.$p.'">'.$p.'</a>&nbsp;';
  }
  if ($page < $last_page) $tag .= '| <a href="index.php?page='.($page+1).'">Next</a>';

  return $tag;
}

//
// count(*)
//
$pdo = getMyDatabase();
$limit = 30;

$sql = 'FROM entries e LEFT JOIN authors a ON e.author=a.screenname';
$stmt = $pdo->prepare('SELECT count(*) '.$sql);
$result = $stmt->execute();
$r = $stmt->fetch();
$cnt = (int)$r[0];
$last_page = (int)(($cnt + $limit - 1) / $limit);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$has_next = ($offset + $limit < $cnt) ? true : false;

# printf("cnt=%d, limit=%d, offset=%d, has_next=%s<br>\n",
#        $cnt, $limit, $offset, ($has_next ? 'true' : 'false'));
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
 <link rel="stylesheet" type="text/css" href="index.css" />
</head>
<body>
<div id="document">
<div class="contents">
<div class="contents_main">
<div class="main">

<ul>
<li><div align="center"><?php echo pager($page, $last_page) ?></div><br/><hr/></li>
<?php
//
// *
//
$stmt = $pdo->prepare('SELECT * '.$sql.' ORDER BY e.id DESC LIMIT ? OFFSET ?');
$result = $stmt->execute(array($limit, $offset));

foreach ($stmt->fetchAll() as $r)
{
  list($tweet_id, $published, $title, $content, $screenname, $tweet_url,
       $dummy, $name, $person_url, $icon_url) = $r;

  $published_jst = jst(preg_replace('/[TZ]/', ' ', $published));
  if (!$published_jst) continue;

  echo <<<EOD
<li class="list_item">
<div class="list_box">
 <div class="list_left_wrap">
  <div class="list_img_wrap"><a href="${person_url}"><img class="twttrimg" src="${icon_url}" /></a></div>
 </div>
 <div class="list_body">
  <div class="tweet">${content}</div>
  <div class="status">
    <a href="${tweet_url}">${screenname}</a><br>
    <span>${published_jst}</span>
  </div>
 </div>
</div>
EOD;
}

?>
<li><div align="center"><?php echo pager($page, $last_page) ?></div></li>
</ul>

</div>
</div>
</div>
</div>
</body>
</html>
