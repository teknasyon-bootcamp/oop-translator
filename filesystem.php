<?php

class FileSystem
{
  /*
  protected string $path;

  public function __construct(string $path) { 
    $this->path = $path;
  }
  */

  public function __construct(protected string $path) {  }
  
  public function load($locale, $group) {
      $fullPath = "{$this->path}/{$locale}/{$group}.php";

      return $this->loadFile($fullPath);
  }
  
  protected function loadFile(string $path) {
    if (!is_file($path)) {
      throw new RuntimeException("Dosya bulunamadı: {$path}");
    }

    return require $path;
  }
}

