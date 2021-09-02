<?php

class Translator
{
  protected array $loaded = [];

  public function __construct
  (
    protected FileSystem $fileSystem,
    protected string $locale,
  ) {  }

  public function get(
    string $key,
    array $args = [],
    ?string $locale = null,
    ?bool $single = null,
    ?int $count = null,
  ): string|array
  {
    $locale ??= $this->locale;

    [$group, $item] = $this->parseKey($key);
    
    /*
    $result = $this->parseKey($key);
    $group = $result[0];
    $item = $result[1];
    */

    $line = $this->getLine($group, $locale, $item, $args);
    if (!is_null($line)) {
      return $line;
    }

    return $key;
  }

  protected function getLine(
    string $group,
    string $locale,
    ?string $item = null,
    array $args = [],
  ): array|string|null
  {
    $this->load($group, $locale);

    $line = $this->findLine($this->loaded[$group][$locale], $item);

    if (is_string($line)) {
      return $this->replaceArgs($line, $args);
    }

    if (is_array($line) && count($line) > 0) {
      foreach ($line as $key => $value) {
        $line[$key] = $this->replaceArgs($value, $args);
      }

      return $line;
    }

    return null;
  }

  protected function replaceArgs(string $line, array $args = []): string
  {
    if(empty($args)) {
      return $line;
    }

    // $args = [
    //  "name" => "Eray",
    //  "surname" => "Aydın",
    // ]
    // $line = "User Details of ':name :surname'"
    foreach ($args as $key => $value) {
      $line = str_replace(":".$key, $value, $line);
    }
    // $line = "User Details of 'Eray Aydın'"
    
    return $line;
  }

  protected function findLine(array $array, ?string $key): array|string|null
  {
    if (is_null($key))
    {
      return $array;
    }

    if (!str_contains($key, '.')) {
      return $array[$key] ?? null;
    }

    // info.name
    // ["info", "name"]
    // a.b.c.d.e
    // ["a", "b", "c", "d", "e"]
    foreach(explode(".", $key) as $segment) {
      if (array_key_exists($segment, $array)) {
        $array = $array[$segment];
      } else {
        return $key;
      }
    } 

    return $array;
  }

  protected function load(string $group, string $locale): void
  {
    if ($this->isLoaded($group, $locale)) {
      return;
    }

    $this->loaded[$group][$locale] = $this->fileSystem->load($locale, $group);
  }

  protected function isLoaded(string $group, string $locale): bool
  {
    return isset($this->loaded[$group][$locale]);
  }

  protected function parseKey(string $key): array
  {
    $segments = explode(".", $key);

    $group = $segments[0];
    $item = implode(".", array_slice($segments, 1));

    return [$group, $item];
  }
}

