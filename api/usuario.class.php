<?php

//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);
//require_once 'conexaoPDO.php';
//print_r($conexao);


class CUsuario {

    public $conexao;
    //  private $erro;
    //variaveis contidas na tabela
    protected $cpf;
    protected $nome;

    // protected $nome_aplicador;  // tb_aplicador
    // Metodo construtor setamos aqui o que queremos que ele faça ao criar o objeto
    function __construct($conexao) {
        $this->conexao = $conexao;
        //criamos a nossa conexao com o banco de dados e selecionamos o banco
    }

    public function buscarLogin($login, $senha) {
  $fp = fopen('databucalogin.txt', 'w');
        // verifica login do ldap
        include_once  'verifica_ldap.php';
        $resplogin = testar_login($login, $senha);
       $VETFORM= print_r($resplogin,true);
  
         fwrite($fp, "form=>".$VETFORM."<==");
        
         
        if ($resplogin['logado'] === 1) {
            // -------------- verifica se tem cgm -----------------

           $dados = array();
            $sql = "SELECT z01_numcgm,z01_nome FROM protocolo.cgm  "
                    . " WHERE  z01_cgccpf='$resplogin[cpf]' limit 1";

          
            fwrite($fp, "SQL=>" . $sql . "<==");
            // fclose($fp);
            $sql = $this->conexao->prepare($sql);

            $sql->execute();
            if ($sql->rowCount() > 0) {
                $dados = $sql->fetch(PDO::FETCH_ASSOC);
                $dados['token'] = $this->geratoken($dados);

                $DADOSUSU = print_r($dados, true);

                // $fp = fopen('datatoken.txt', 'w');
                fwrite($fp, "dados usu=>))" . $DADOSUSU . "((<==");

                fclose($fp);
               
            }
        }
        
         return $dados;
    }
            

        private

        function geratoken($param) {
            // header indica o tipo do token JWT e o algoritimo utilizado HS256
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];
            // converter o array em objeto
            $header = json_encode($header);
            // codificar dados em base64
            $header = base64_encode($header);
            //O payload é o corpo do JWT, recebe as informações que precisa armazenar
            // iss - o domínio da aplicação que gera o token
            // aud - define o domínio que pode usar o token
            // exp - data de vencimento do token
            // 7dias ; 24horas, 60 min; 60 segunods
            // $duracao = time() + (7 * 24 * 60* 60);
            $duracao = time() + (10 * 60); // 10 minutos
            $payload = [
                'iss' => '192.168.22.13',
                'aud' => '192.168.22.13',
                'exp' => $duracao,
                'numcgm' => $param['z01_numcgm'],
                'nome' => $param['z01_nome']
                               
            ];
            // converter para objeto
            $payload = json_encode($payload);
            //converter para base64
            $payload = base64_encode($payload);
            // O signature é a assinatura. pegar o header e o payload e codificar com o algoritimo sha256, junto com a chave
            $chave = "APL179191207112ANGULAR"; // inventada por você
            // gera um valor  de hash com chave usando o metodo HMAC
            $signature = hash_hmac('sha256', "$header.$payload", $chave, true);
            //converter para base64
            $signature = base64_encode($signature);
            $token = "$header.$payload.$signature";
            return $token;
        }

    }

//$obj = new CUsuario($conexao);
//print_r($obj);
//$dados = $obj->BuscarTodos();
// $op=$obj->inserir("TESTANDO555555");
//print "rese=($op)";
//$dados = $obj->BuscarTodos();
//print_r($dados);
//print_r($obj->buscarListaId(3));
//$op=$obj->alterar(1, 'sacocheio');
// print "rese=($op)";
//print_r($obj->deletar(8));
//print_r($obj->totalLinhas());
//phpinfo();

    