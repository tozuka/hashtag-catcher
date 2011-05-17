<?php
require_once('Database_pdo.class.php');

class Entry extends Database_pdo {
  public $id, $published, $title, $content, $author, $alternate_url;

  protected function getTableName()
  {
    return 'entries';
  }

  public function __construct($id, $published, $title, $content, $author, $alternate_url)
  {
    $this->id                    = $id;
    $this->published             = $published;
    $this->title                 = $title;
    $this->content               = $content;
    $this->author                = $author;
    $this->alternate_url         = $alternate_url;
  }

  protected function bindValues($stmt)
  {
    $stmt->bindValue(':id', (string)$this->id, PDO::PARAM_STR);
    $stmt->bindValue(':published', $this->published, PDO::PARAM_STR);
    $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
    $stmt->bindValue(':author', $this->author, PDO::PARAM_STR);
    $stmt->bindValue(':alternate_url', $this->alternate_url, PDO::PARAM_STR);
  }

  protected function getCreateSQL()
  {
    return 'INSERT INTO entries (id,published,title,content,author,alternate_url) VALUES (:id,:published,:title,:content,:author,:alternate_url)';
  }

  protected function getUpdateSQL()
  {
    return 'UPDATE entries SET published=:published,title=:title,content=:content,author=:author,alternate_url=:alternate_url WHERE id=:id';
  }

  public static function maxId()
  {
    return self::st_fetchOne('SELECT max(id) FROM entries');
  }

}
