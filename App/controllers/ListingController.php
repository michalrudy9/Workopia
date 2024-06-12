<?php

namespace App\Controllers;

use Framework\Database;

class ListingController
{
    protected $db;

    public function __construct()
    {
        $config = require_once basePath("config/db.php");
        $this->db = new Database($config);
    }

    public function index(): void
    {
        $listings = $this->db->query("select * from listings ")->fetchAll();

        loadView("listings/index", ["listings" => $listings]);
    }

    public function create(): void
    {
        loadView("listings/create");
    }

    public function show($params): void
    {
        $id = $params["id"] ?? "";

        $params = [
            "id" => $id,
        ];

        $listing = $this->db
            ->query("select * from listings where id = :id", $params)
            ->fetch();

        if (!$listing) {
            ErrorController::notFound("Listing not found!");
            return;
        }

        loadView("listings/show", ["listing" => $listing]);
    }
}
