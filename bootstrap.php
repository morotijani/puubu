<?php
    
    // START SESSION
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // AUTO LOAD VENDOR FILES
    require __DIR__  . '/vendor/autoload.php';

    // LOAD DOTENV
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
