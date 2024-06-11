<?php

$config = require_once basePath("config/db.php");
$db = new Database($config);

$listings = $db->query("select * from listings limit 6")->fetchAll();

loadView("home", ["listings" => $listings]);
