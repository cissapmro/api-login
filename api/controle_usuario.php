<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
require_once 'conexaoPDO.php';
require_once 'usuario.class.php';

$usuario = new CUsuario($conexao);
header("Content-type: application/json; charset=utf-8");
//header('Content-Type: application/javascript');
$form = json_decode(file_get_contents('php://input'),true);
//print "acao=".$_REQUEST['action'];
//$_REQUEST['action']="getlogin";
//$_POST['item']="teste de controle novo";
//Listar Registro



$VETFORM= print_r($form,true);
   $fpx = fopen('datacontrole.txt', 'w');
         fwrite($fpx, "form=>".$VETFORM."<==");
         




if(isset($_REQUEST['action']) && $_REQUEST['action'] == "getLogin"){
  $login = $form['login'];
   $senha = $form['senha'];
 
  // $fp = fopen('datacontrolelogin.txt', 'w');
  //      fwrite($fpx, "email=>". $login."<==senha".$senha);
//    $
   
    $res=$usuario->buscarLogin($login,$senha);
    $VETres= print_r($res,true);
     fwrite($fpx, "form1=>".$VETres."<==");
     foreach ($res as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $keyx => $valuex) {
//               
                                  $res[$key][$keyx] = utf8_encode($valuex);        
            }
        } else {
            $res[$key] = utf8_encode($value);
        }
     }
     $res['login']=$login;
//         $VETres= print_r($res,true);
//     fwrite($fpx, "form2=>".$VETres."<==");
        
       echo json_encode($res);



}


if(isset($_REQUEST['action']) && $_REQUEST['action'] == "update" ) {
    
      
//        $fp = fopen('datacontrole.txt', 'w');
//         fwrite($fp, "desc=>".$desc."<== id=".$id);
//         fclose($fp);
//    $res=$usuario->alterar($form);
//    echo json_encode($res);
}