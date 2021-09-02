<?php

require "filesystem.php";
require "translator.php";

IFileSystem

$fs = new FileSystem(__DIR__."/diller");

$translate = new Translator(
  __DIR__."/diller",
  $_GET['lang']
);

echo "<h1>".$translate->get('user.index')."</h1>";

echo "<h3>".$translate->get('user.show', ['name' => "Eray", 'surname' => "Aydın"])."</h3>";
echo "<p>".$translate->get('user.info.name').": Eray</p>";

$translate->get('user.info')['name'];

echo "<p>".$translate->get('user.olmayanBirAnahtar')."</p>"; // <p>user.olmayanBirAnahtar</p>

echo "<h1>".$translate->get('user.index', locale: "en")."</h1>";

$translate->get('user.count', single: true); // Kullanıcı
$translate->get('user.count', single: false); // Kullanıcılar
$translate->get('user.count', count: 1); // 1 Kullanıcı
$translate->get('user.count', count: 10); // 10 Kullanıcı

var_dump($translate->get("user.index"));
var_dump($translate->get("user.show", ['name' => "Eray"]));
var_dump($translate->get('user.info.name'));
var_dump($translate->get('user.index', locale: "en"));
