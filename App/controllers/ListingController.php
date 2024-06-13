<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

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

    public function store(): void
    {
        $allowedFields = [
            "title",
            "description",
            "salary",
            "tags",
            "company",
            "address",
            "city",
            "state",
            "phone",
            "email",
            "requirements",
            "benefits",
        ];

        $newListingData = array_intersect_key(
            $_POST,
            array_flip($allowedFields)
        );

        $newListingData["user_id"] = 1;
        $newListingData = array_map("sanitize", $newListingData);
        $requiredFields = ["title", "description", "email", "city", "state"];
        $errors = [];

        foreach ($requiredFields as $field) {
            if (
                empty($newListingData[$field]) ||
                !Validation::string($newListingData[$field])
            ) {
                $errors[$field] = ucfirst($field) . " is required";
            }
        }

        if (!empty($errors)) {
            loadView("listings/create", [
                "errors" => $errors,
                "listing" => $newListingData,
            ]);
        } else {
            echo "Success";
        }
    }
}
