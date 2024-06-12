<?php

$config = require_once basePath("config/db.php");
$db = new Database($config);

$id = $_GET["id"] ?? "";

$params = [
    "id" => $id,
];

$listing = $db
    ->query("select * from listings where id = :id", $params)
    ->fetch();

loadView("listings/show", ["listing" => $listing]);
