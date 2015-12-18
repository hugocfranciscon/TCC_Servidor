<?php
header('Content-Type: text/html; charset=utf-8');

/**
 * Realiza a conexão com o DB
 */
class DB_Connect {
 
    // constructor
    function __construct() {
         
    }
 
    // destructor
    function __destruct() {
        // $this->close();
    }
 
    public function connect() {
        require_once 'include/Config.php';
        // connecting to mysql
        $con = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die(mysql_error());
        mysql_set_charset("utf8",$con);
       
        // UTF8
        mysql_query("SET NAMES utf8");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
        mysql_query('SET character_set_server=utf8');
        
        // Seleciona o banco de dados
        mysql_select_db(DB_DATABASE) or die(mysql_error());

        // Retorna a conexão
        return $con;
    }
 
    // Encerra conexão
    public function close() {
        mysql_close();
    }
 
}
 
?>