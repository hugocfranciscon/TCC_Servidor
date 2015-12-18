<?php
/**
 * Configura as variáveis do DB
 */
mb_internal_encoding("UTF-8"); 
ini_set("mbstring.internal_encoding","UTF-8");
ini_set("mbstring.func_overload",7);
 header('Content-Type: text/html; charset=utf-8');

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "1234");
define("DB_DATABASE", "mydb");
?>