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
        $requiredFields = [
            "title",
            "description",
            "salary",
            "email",
            "city",
            "state",
        ];
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
            $fields = [];

            foreach ($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(", ", $fields);
            $values = [];

            foreach ($newListingData as $field => $value) {
                if ($value === "") {
                    $newListingData[$field] = null;
                }
                $values[] = ":" . $field;
            }
            $values = implode(", ", $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
            $this->db->query($query, $newListingData);

            redirect("listings");
        }
    }

    public function destroy(array $params): void
    {
        $id = $params["id"];

        $params = ["id" => $id];

        $listing = $this->db
            ->query("SELECT * FROM listings WHERE id = :id", $params)
            ->fetch();

        if (!$listing) {
            ErrorController::notFound("Listing not found");
            return;
        }

        $this->db->query("DELETE FROM listings WHERE id = :id", $params);

        $_SESSION["success_message"] = "Listing deleted successfully";

        redirect("/listings");
    }

    public function edit($params): void
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

        loadView("listings/edit", ["listing" => $listing]);
    }

    public function update(array $params): void
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

        $updateValues = [];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map("sanitize", $updateValues);

        $requiredFields = [
            "title",
            "description",
            "salary",
            "email",
            "city",
            "state",
        ];

        $errors = [];

        foreach ($requiredFields as $field) {
            if (
                empty($updateValues[$field]) ||
                !Validation::string($updateValues[$field])
            ) {
                $errors[$field] = ucfirst($field) . " is required!";
            }
        }

        if (!empty($errors)) {
            loadView("listings/edit", [
                "listing" => $listing,
                "errors" => $errors,
            ]);
            exit();
        } else {
            $updateFields = [];

            foreach (array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }
            $updateFields = implode(", ", $updateFields);
            $updateQuery = "UPDATE listings SET {$updateFields} WHERE id = :id";
            $updateValues["id"] = $id;

            $this->db->query($updateQuery, $updateValues);
            $_SESSION["success_message"] = "Listing updated!";

            redirect("/listings/" . $id);
        }
    }
}
