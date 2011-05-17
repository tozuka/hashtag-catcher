#!/bin/sh

DB=./tweets.db

echo 'SELECT * FROM entries e LEFT JOIN authors a ON e.author=a.screenname;' | /usr/bin/sqlite3 $DB | /bin/awk -F"|" '
BEGIN {
  print "Content-type: text/html";
  print "";
  print "<html><body><table border=0>";
}

{
# tweet_id   = $1;
  published  = $2; gsub(/[TZ]/, " ", published);
  title      = $3;
  content    = $4;
  screenname = $5;
  tweet_url  = $6;

  name       = $8;
  person_url = $9;
  icon_url   = $10;

  print "<tr>"
  printf("  <td><a href=\"%s\"><font size=2>%s</font></a></td>\n", tweet_url, published);
  printf("  <td valign=\"top\"><a href=\"%s\"><img src=\"%s\" border=\"0\">%s</a></td>", person_url, icon_url, screenname);
  printf("  <td valign=\"top\">%s</td>\n", content);
  print "</tr>"
}

END {
  print "</table></body></html>";
}'

