<?php
/**
 * Description of ConexaoPDO
 *
 * @author maria.figueiredo
*/
header('Access-Control-Allow-Origin: * ');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, POST, GET,PATCH, OPTIONS, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Access-Control-Allow-Headers: Origin, Accept');
header("Access-Control-Expose-Headers: Content-Length, X-JSON");
header('Access-Control-Max-Age: 86400') ;
  header("Access-Control-Allow-Headers: *");
//
//date_default_timezone_set('America/Sao_Paulo');
//phpinfo();
try {
    $conexao = new \PDO('pgsql:dbname=ecidade;host=192.168.22.7', "saero", "ecce80ce235f2eaa540c15559897de8a");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Conectado com sucesso!"; 
} catch (PDOException $e) {
    echo "Erro de Conexão".$e->getMessage(); 
    exit;
}
