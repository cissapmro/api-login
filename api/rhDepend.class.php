<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
//require_once 'conexaoPDO.php';
//print_r($conexao);
class CRhDepend {

    public $conexao;
    //  private $erro;
    //variaveis contidas na tabela

    protected $rh31_codigo;
    protected $rh31_regist;
    protected $rh31_nome ;
    protected $rh31_dtnasc;
    protected $rh31_gparen;
    protected $rh31_depend ;
    protected $rh31_irf;
    protected $rh31_especi ;
    protected $rh31_cpfdepend;
   

    // protected $nome_aplicador;  // tb_aplicador
    // Metodo construtor setamos aqui o que queremos que ele faça ao criar o objeto
    function __construct($conexao) {
        $this->conexao = $conexao;
        //criamos a nossa conexao com o banco de dados e selecionamos o banco
    }

    public function BuscarTodos($inicio, $quantidade, $registro) {

        $dados = array();
        $sql = "SELECT * FROM pessoal.rhdepen where rh31_regist=$registro LIMIT $inicio OFFSET $quantidade";
        $fp = fopen('datalistALL.txt', 'w');
        fwrite($fp, "desc=>" . $sql . "<==");
        fclose($fp);
        $sql = $this->conexao->prepare($sql);
        $sql->execute();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
            $json_data = $dados;
            //   echo json_encode($json_data);
        }
        return $json_data;
    }

    public function inserir($formdep) {

        $fp = fopen('datainsertdep.txt', 'w');
     
     //   fclose($fp);
        $dados = array();
        $sql = "SELECT x.rh01_regist from pessoal.rhpessoal x inner join rhpesdoc y on rh01_regist=rh16_regist  WHERE rh01_numcgm = '$formdep[numcgm]' ";
        $sql = $this->conexao->prepare($sql);
              $sql->execute();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchall(PDO::FETCH_ASSOC);
            foreach ($dados AS $key => $value)
            { $formdep['regist']=$value['rh01_regist'];
                $sql = "INSERT INTO pessoal.rhdepend ( rh31_regist ,
                                               rh31_nome ,
                                                rh31_dtnasc,
                                                rh31_gparen,
                                                rh31_depend,
                                                rh31_irf ,
                                               rh31_especi ,
                                              rh31_cpfdepend   ) 
                        VALUES ( '$formdep[regist]','$formdep[nome_depend]',
                                     '$formdep[dtnasc_depend]',
                                       '$formdep[gparent]',
                                       null,
                                      null,
                                     null,
                                        '$formdep[cpf_depend]');";
                   fwrite($fp, "sql=>" . $sql . "<==");
        $sql = $this->conexao->prepare($sql);
        //    $sql->bindValue(':desc', $desc);
        $sql->execute();

        if ($sql) {
            // ECHO "INSERIU";
            $retorno=true;
        } else {
            $retorno= false;
            }
            
        }
    }
    return $retorno;
    }

    public function buscarRegistro($cgm) {

         $mesusu=date('m')-1;
         $anousu=date('Y');
        $dados = array();
        $sql = "SELECT distinct x.rh31_codigo as codigo,x.rh31_regist as regist,x.rh31_nome as nome, x.rh31_dtnasc as dtnasc,x.rh31_gparen as gparent,x.rh31_cpfdepend as cpf 
from pessoal.rhdepend x where rh31_regist in (select distinct rh01_regist from  rhpessoal 
 inner join pessoal.rhpessoalmov rm ON  rh01_regist=rh02_regist and rh02_mesusu=$mesusu and rh02_anousu=$anousu
left join pessoal.rhpesrescisao pr ON rh05_seqpes=rh02_seqpes    WHERE rh01_numcgm = '$cgm' and rh05_recis is null)";
        
//        $sql = "SELECT distinct x.rh31_codigo as codigo,x.rh31_regist as regist,x.rh31_nome as nome, x.rh31_dtnasc as dtnasc,x.rh31_gparen as gparent,x.rh31_cpfdepend as cpf"
//                . " from pessoal.rhdepend x inner join rhpessoal y on rh01_regist=rh31_regist "
//                . " inner join pessoal.rhpessoalmov rm ON  rh01_regist=rh02_regist left join pessoal.rhpesrescisao pr ON rh05_seqpes=rh02_seqpes and rh05_recis is null "
//                . "  WHERE rh01_numcgm = '$cgm'  ";
      
         $fp = fopen('dataibuscadepend.txt', 'w');
        fwrite($fp, "sql=>" . $sql . "<==");
        fclose($fp);
        $sql = $this->conexao->prepare($sql);
              $sql->execute();
        if ($sql->rowCount() > 0) {
            $dados = $sql->fetchall(PDO::FETCH_ASSOC);
        }else
        {
 $dados=array();
//              $dados=array("codigo"=>'',"regist"=>'' ,"nome"=>'' ,"dtnasc"=>'',
//                        "gparen"=>'',"depend"=>'' ,"cpf"=>''); 
        }
        return $dados;
    }

    public function alterar($formdep) {
//=======================pegar numero de registros===============
        
        $fp = fopen('dataupdatedepend.txt', 'w');
        
        
   
              $codigo=$formdep['codigo'];
               
             
                    $sqlup = "UPDATE pessoal.rhdepend SET "
                            . " rh31_regist='".$formdep['regist']."',
                       rh31_nome='".$formdep['nome_depend']."',
                        rh31_dtnasc= '".$formdep['dtnasc_depend']."',
                        rh31_gparen= '".$formdep['gparent']."',
                        rh31_depend= null,
                        rh31_irf= 0,
                        rh31_especi= null,
                        rh31_cpfdepend = '".$formdep['cpf_depend']."'
                       WHERE rh31_codigo=$codigo";
                    
                                     
                    fwrite($fp, "SQL=>" . $sqlup . "<==");
                  
                    $sqlup = $this->conexao->prepare($sqlup);
                    $sqlup->execute();
                    if ($sqlup) {
                        $retorno = true;
                    } else {
                        $retorno = false;
                    }
              
            
        fwrite($fp, "retorno".$retorno);
        return $retorno;
    }

    public function deletar($id) {

        $sql = "DELETE FROM pessoal.rhdepend WHERE rh31_regist = '$form[regist]' and rh31_codigo=$form[codigo]";
        $sql = $this->conexao->prepare($sql);

        $sql->execute();
        if ($sql) {
            return true;
        } else {
            return false;
        }
    }

 
    
}

//$obj = new CRhPesDoc($conexao);
//print_r($obj);
//$dados = $obj->buscarGrausInstr();
 //$op=$obj->inserir("TESTANDO555555");
//print "rese=($op)";
//$dados = $obj->BuscarTodos();
//print_r($dados);
//print_r($obj->buscarListaId(3));
//$op=$obj->alterar(1, 'sacocheio');
// print "rese=($op)";
//print_r($obj->deletar(8));
//print_r($obj->totalLinhas());
//phpinfo();