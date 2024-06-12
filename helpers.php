<?php

function basePath(string $path = ""): string
{
    return __DIR__ . "/" . $path;
}

function loadView(string $name, array $data = []): void
{
    $viewPath = basePath("App/views/{$name}.php");

    if (file_exists($viewPath)) {
        extract($data);
        require_once $viewPath;
    } else {
        echo "View '{$name} not found!'";
    }
}

function loadPartial(string $name): void
{
    $partialPath = basePath("App/views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        require_once $partialPath;
    } else {
        echo "Partial '{$name} not found!'";
    }
}

function inspect(mixed $value): void
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
}

function inspectAndDie(mixed $value): void
{
    echo "<pre>";
    die(var_dump($value));
    echo "</pre>";
}

function formatSalary(string $salary): string
{
    return '$' . number_format(floatval($salary));
}
