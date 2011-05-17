<?php

class Author extends Database_pdo {
  public $screenname, $name, $uri, $profile_image_url;

  protected function getTableName()
  {
    return 'authors';
  }

  public function __construct($screenname, $name, $uri, $profile_image_url) 
  {
    $this->screenname        = $screenname;
    $this->name              = $name;
    $this->uri               = $uri;
    $this->profile_image_url = $profile_image_url;
  }

  protected function bindValues($stmt)
  {
    $stmt->bindValue(':screenname', $this->screenname, PDO::PARAM_STR);
    $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
    $stmt->bindValue(':uri', $this->uri, PDO::PARAM_STR);
    $stmt->bindValue(':profile_image_url', $this->profile_image_url, PDO::PARAM_STR);
  }

  protected function getCreateSQL()
  {
    return 'INSERT INTO authors VALUES (:screenname,:name,:uri,:profile_image_url)';
  }

  protected function getUpdateSQL()
  {
    return 'UPDATE authors SET name=:name,screenname=:screenname,uri=:uri,profile_image_url=:profile_image_url WHERE screenname=:screenname';
  }

  protected function create()
  {
    $this->execute($this->getCreateSQL());

    $this->has_record = true;
  }

  protected function checkExistence()
  {
    return self::st_fetchOne('SELECT count(*) FROM '.$this->getTableName().' WHERE screenname="'.$this->screenname.'"');
  }

}
