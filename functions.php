<?php

require_once "config.php";

function e($value)
{
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION["user_id"]);
}

function isAdmin()
{
    return isset($_SESSION["user_role"]) &&
           $_SESSION["user_role"] === "admin";
}

function requireLogin()
{
    if (!isLoggedIn()) {
        redirect("login.php");
    }
}

function requireAdmin()
{
    if (!isAdmin()) {
        redirect("login.php");
    }
}

function jobTypeLabel($type)
{
    $types = [
        "full-time" => "Full-time",
        "part-time" => "Part-time",
        "internship" => "Internship",
        "contract" => "Contract"
    ];

    return $types[$type] ?? ucfirst($type);
}

function formatDate($date)
{
    return date("d M Y", strtotime($date));
}
?>