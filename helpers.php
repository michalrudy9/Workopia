<?php

function basePath(string $path = ""): string
{
    return __DIR__ . "/" . $path;
}

function loadView(string $name): void
{
    $viewPath = basePath("views/{$name}.php");

    inspectAndDie($viewPath);
    
    if (file_exists($viewPath)) {
        require_once $viewPath;
    } else {
        echo "View '{$name} not found!'";
    }
}

function loadPartial(string $name): void
{
    $partialPath = basePath("views/partials/{$name}.php");

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
