<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require_once basePath("config/db.php");
        $this->db = new Database($config);
    }

    public function login(): void
    {
        loadView("users/login");
    }
    public function create(): void
    {
        loadView("users/create");
    }
}
