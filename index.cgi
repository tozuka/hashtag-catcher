#!/bin/sh

#DB=/var/bot/twitter/ykara/tweets.db
DB=tweets.db

echo 'SELECT * FROM entries e LEFT JOIN authors a ON e.author=a.screenname;' | /usr/bin/sqlite3 $DB | /bin/awk -F"|" '
BEGIN {
  print "Content-type: text/html";
  print "";
  print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">"
  print "<head>"
  print " <link rel=\"stylesheet\" type=\"text/css\" href=\"index.css\" />"
  print "</head>"
  print "<body>"
  print "<div id=\"document\">"
  print "<div class=\"contents\">"
  print "<div class=\"contents_main\">"
  print "<div class=\"main\">"
  print "<ul>"
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

  print "<li class=\"list_item\">"
  print "<div class=\"list_box\">"
  print " <div class=\"list_left_wrap\">"
  print "  <div class=\"list_img_wrap\"><a href=\"" person_url "\"><img class=\"twttrimg\" src=\"" icon_url "\" /></a></div>"
  print " </div>"
  print " <div class=\"list_body\">"
  print "  <div class=\"tweet\">" content "</div>"
  print "  <div class=\"status\">"
  print "    <a href=\"" tweet_url "\">" screenname "</a><br>"
  print "    <span>" published "</span>"
  print "  </div>"
  print " </div>"
  print "</div>"
  #printf("  <td><a href=\"%s\"><font size=2>%s</font></a></td>\n", tweet_url, published);
  #printf("  <td valign=\"top\"><a href=\"%s\"><img src=\"%s\" border=\"0\">%s</a></td>", person_url, icon_url, screenname);
  #printf("  <td valign=\"top\">%s</td>\n", content);
  #print "</tr>"
}

END {
  print "</ul>"
  print "</div>"
  print "</div>"
  print "</div>"
  print "</div>"
  print "</body>"
  print "</html>"
}'

