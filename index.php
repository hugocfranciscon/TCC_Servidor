
<?php
mb_internal_encoding("UTF-8");
 header('Content-Type: text/html; charset=utf-8');
 
/**
 * File to handle all API requests
 * Accepts GET and POST
 * 
 * Each request will be identified by TAG
 * Response will be JSON data
 
  /**
 * check for POST request 
 */
 
 $ip = "177.134.48.145:5001";
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];
 
    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
 
    // response Array
    $response = array("tag" => $tag, "erro" => FALSE);
 
    
    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $login = $_POST['login'];
        $senha = $_POST['senha'];
 
        // check for user
        $user = $db->getUsuario($login, $senha);
        if ($user["tipoDeUsuario"] == 1) {
            // user found
            $response["erro"] = FALSE;
            $response["user"]["idUsuario"] = $user["idUsuario"];

            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Email ou senha incorretos!";
            echo json_encode($response);
 
        }
    }else if ($tag == 'insereUsuario'){ 
        
        // Request type is Register new user
        $nome = $_POST['nome'];
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $tipoDeUsuario = $_POST['tipoDeUsuario'];
        $admin = (isset($_POST['admin'])) ? 1 : 0;
        
      
 
        // check if user is already existed
        if ($db->isUserExisted($login)) {
            // user is already existed - error response
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Usuario ja existe";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->salvaUsuario($nome, $login, $senha, $email, $telefone, $endereco, $tipoDeUsuario, $admin);
            if ($user) {
                // user stored successfully
                $idUsuario = $user['idUsuario'];
                $tipoDeUsuario = $user['tipoDeUsuario'];

                if($tipoDeUsuario == 1){    // Cadastra Médico
                    $especializacao = $_POST['especializacao'];
                    $medico = $db->salvaMedico($especializacao, $idUsuario, $tipoDeUsuario);
            
                }
                else if ($tipoDeUsuario == 2){  // Cadastra Enfermeira
                    $ala = $_POST['ala'];
                    $enfermeira = $db->salvaEnfermeira($ala, $tipoDeUsuario, $idUsuario);
               
                }
                else if ($tipoDeUsuario == 3){  // Cadastra Recepcionista
                    $recepcionista = $db->salvaRecepcionista($tipoDeUsuario, $idUsuario);
              
                }
               header("Location: http://". $ip."/PhpProject2/admin.php"); 
            }
                
            
        }
        
    }else if ($tag == 'getPacientes'){
        $idMedico = $_POST['idMedico'];
        
        $pacientes = $db->getPacientes($idMedico);
        if ($pacientes){
            $response["erro"] = FALSE;
            $response["pacientes"]= $pacientes; 
            echo json_encode($response);
    
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Medico nao possui pacientes!";
            echo json_encode($response);
        }
        
    // Obtem informacoes de um paciente
    }else if($tag == 'getPaciente'){
        $idPaciente = $_POST['idPaciente'];
        $paciente = $db->getPaciente($idPaciente);
        $diagnostico = $db->getDiagnosticoDoPaciente($idPaciente);
        
        if ($paciente){
            $response["erro"] = FALSE;
            $response["paciente"]["nome"]= $paciente["nome"];
            $response["paciente"]["dataDeNascimento"]= $paciente["dataDeNascimento"];
            $response["paciente"]["descricao"]= $paciente["descricao"];
            $response["paciente"]["quarto"]= $paciente["Quarto_idQuarto"];
            $response["paciente"]["dataDeAdmissao"]= $paciente["dataDeAdmissao"];
            
            if ($diagnostico){
                 $response["paciente"]["diagnostico"]= $diagnostico;
            }
            else{
                $response["paciente"]["diagnostico"]= "";
            }
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Paciente nao encontrado!";
        }
        echo json_encode($response);
        
    // Salva registration Id (GCM)
    }else if($tag == 'reg_id'){
        $idMedico = $_POST['idMedico'];
        $reg_id = $_POST['reg_id'];
        
        $storeRegId = $db->storeRegId($idMedico, $reg_id);
        if ($storeRegId){
            $response["erro"] = FALSE;
            $response["user"]["idMedico"] = $storeRegId["idMedico"];
            $response["user"]["reg_id"] = $storeRegId["reg_id"];
            echo json_encode($response);
            
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "reg_id nao salvo!";
            echo json_encode($response);
        }
  
    }else if ($tag == 'salvaSinais'){
        $idPaciente = $_POST['idPaciente'];
        $temperatura = $_POST['temperatura'];
        $taxaBatimentos = $_POST['taxaBatimentos'];
        $glicose = $_POST['glicose'];
        $saturacaoOxigenio = $_POST['saturacaoOxigenio'];
        $pressaoSistolica = $_POST['pressaoSistolica'];
        $pressaoDiastolica = $_POST['pressaoDiastolica'];
        $funcaoPulmonar = $_POST['funcaoPulmonar'];
        
        $sinais = $db->salvaSinais($idPaciente, $temperatura, $taxaBatimentos, $glicose, $saturacaoOxigenio, $pressaoSistolica, $pressaoDiastolica, $funcaoPulmonar);
        if ($sinais){
            
            $response["erro"] = FALSE;

            $mensagem = $db->verificaSinais($idPaciente, $temperatura, $taxaBatimentos, $glicose, $saturacaoOxigenio, $pressaoSistolica, $pressaoDiastolica, $funcaoPulmonar);
            if ($mensagem == false){
                 $response["alerta"] = FALSE;
            }else{
                $db->salvaAlerta($idPaciente, $mensagem);
                $alerta = $db->enviaAlerta($idPaciente, $mensagem, "sinal");
                $response["alerta"] = $alerta;
            }
            echo json_encode($response);
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Sinais nao salvos!";
            echo json_encode($response);
        }
        
    }else if ($tag == 'getSinais'){
        $idPaciente = $_POST['idPaciente'];
        $sinais = $db->getSinais($idPaciente);
        if ($sinais){
            $response["erro"] = FALSE;
            $response["sinais"]["temperatura"] = $sinais["temperatura"];
            $response["sinais"]["taxaBatimentos"] = $sinais["taxaBatimentos"];
            $response["sinais"]["glicose"] = $sinais["glicose"];
            $response["sinais"]["saturacaoOxigenio"] = $sinais["saturacaoOxigenio"];
            $response["sinais"]["pressaoSistolica"] = $sinais["pressaoSistolica"];
            $response["sinais"]["pressaoDiastolica"] = $sinais["pressaoDiastolica"];
            $response["sinais"]["funcaoPulmonar"] = $sinais["funcaoPulmonar"];   
            
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Nao foi encontrado sinais cadastrados para o paciente";
        }
        echo json_encode($response);
        
    }else if ($tag == 'getHistorico'){
        $idPaciente = $_POST['idPaciente'];
        $historico = $db->getHistorico($idPaciente);
        if ($historico){
            $response["erro"] = FALSE;
            $response["historico"]["fumante"] = $historico["fumante"];
            $response["historico"]["usoDeAlcool"] = $historico["usoDeAlcool"];
            $response["historico"]["usoDeDroga"] = $historico["usoDeDroga"];
            $response["historico"]["alergia"] = $historico["alergia"];
            $response["historico"]["infoAdicional"] = $historico["infoAdicional"];   
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Nao foi encontrado o historico do paciente";
        }
        echo json_encode($response);
        
        
    }else if($tag == 'registraEvolucao'){
         $idPaciente = $_POST['idPaciente'];
         $descricao = $_POST['descricao'];
         
         $registraEvolucao = $db->registraEvolucao($idPaciente, $descricao);
         if ($registraEvolucao){
             $response["erro"] = FALSE;
             $response["evolucao"]["idEvolucao"] = $registraEvolucao["idEvolucao"];
             $response["evolucao"]["data"] = $registraEvolucao["data"];
         }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Evolução não registrada";
         }
         echo json_encode($response); 
         
         
    }else if ($tag == 'getMedicamentos'){ 
        $medicamentos = $db->getMedicamentos();
        if ($medicamentos){
            $response["erro"] = FALSE;
            $response["medicamentos"] = $medicamentos;
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Não foram encontrados medicamentos";
        }
        echo json_encode($response); 
        
    }else if ($tag == 'registraRequisicao'){
        $idPaciente = $_POST['idPaciente'];
        $idMedico = $_POST['idMedico'];
        $idMedicamento = $_POST['idMedicamento'];
        $dose = $_POST['doseAdministrada'];
        $descricao = $_POST['descricao'];
        
        $requisicao = $db->registraRequisicao($idPaciente, $idMedico, $idMedicamento,$descricao, $dose);
        if ($requisicao){
            $response["erro"] = FALSE;
            $response["requisicao"] = $requisicao;
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Requisição não registrada";
        }
        echo json_encode($response);
        
    }else if ($tag == 'getDiagnosticos'){
        $diagnosticos = $db->getDiagnosticos();
        if ($diagnosticos){
            $response["erro"] = FALSE;
            $response["diagnosticos"] = $diagnosticos;
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Não foram encontrados diagnosticos";
        }
        echo json_encode($response);
        
    }else if ($tag == 'registraDiagnostico'){
        $idPaciente = $_POST['idPaciente'];
        $idDiagnostico = $_POST['idDiagnostico'];
        $observacoes = $_POST['observacoes'];
        
        $registraDiagnostico = $db->registraDiagnostico($idPaciente, $idDiagnostico, $observacoes);
        if ($registraDiagnostico){
            $response["erro"] = FALSE;
            $response["diagnostico"] = $registraDiagnostico;
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Diagnóstico não registrado!";
        }
        echo json_encode($response);

    }else if ($tag == 'editaPaciente'){
        $idPaciente = $_POST['idPaciente'];
        $nome = $_POST['nome'];
        $dataDeNascimento = $_POST['dataDeNascimento'];
        $sexo = $_POST['sexo'];
        $descricao = $_POST['descricao'];
        $dataDeAdmissao = $_POST['dataDeAdmissao'];
        $quarto = $_POST['quarto'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $medicos = $_POST['medicos'];
           

        $db->apagaMedico_paciente($idPaciente);    
        $db->medico_paciente($medicos, $idPaciente);
        $edita = $db->editaPaciente($idPaciente, $nome, $dataDeNascimento, $sexo, $dataDeAdmissao, $quarto, $descricao, $endereco, $telefone, $email);
        
        if ($edita){
            $response["erro"] = FALSE;
        }else{
            $response["erro"] = TRUE;
            $response["erro_msg"] = "Paciente não editado!";
        }
       
        header("Location: http://". $ip."/PhpProject2/recepcionista.php");
        
        
    }else if ($tag == 'apagaPaciente'){
            $idPaciente = $_POST['idPaciente'];
            $db->apagaMedico_paciente($idPaciente);
            $apaga = $db->apagaPaciente($idPaciente);
            if ($apaga){
                $response["erro"] = FALSE;
            }else{
                $response["erro"] = TRUE;
                $response["erro_msg"] = "Paciente não apagado!";
            }
            
            header("Location: http://". $ip."/PhpProject2/recepcionista.php");
    
            
    }else if ($tag == 'inserePaciente'){
        $nome = $_POST['nome'];
        $dataDeNascimento = $_POST['dataDeNascimento'];
        $sexo = $_POST['sexo'];
        $descricao = $_POST['descricao'];
        $quarto = $_POST['quarto'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];
        $fumante = (isset($_POST['fumante'])) ? 1 : 0;
        $usoDeAlcool = (isset($_POST['usoDeAlcool'])) ? 1 : 0;
        $usoDeDroga = (isset($_POST['usoDeDroga'])) ? 1 : 0;
        $alergia = $_POST['alergia'];
        $infoAdicional= $_POST['infoAdicional'];
        $medicos = $_POST['medicos'];
        
        
   
        $insere = $db->inserePaciente($nome, $dataDeNascimento, $sexo, $quarto, $descricao, $endereco, $telefone, $email,$fumante, $usoDeAlcool, $usoDeDroga, $alergia, $infoAdicional);
        $db->medico_paciente($medicos, $insere["idPaciente"]);
        header("Location: http://". $ip."/PhpProject2/recepcionista.php");
        
    }else if ($tag == 'editaHistorico'){
        $idPaciente = $_POST['idPaciente'];
        $fumante = (isset($_POST['fumante'])) ? 1 : 0;
        $usoDeAlcool = (isset($_POST['usoDeAlcool'])) ? 1 : 0;
        $usoDeDroga = (isset($_POST['usoDeDroga'])) ? 1 : 0;
        $alergia = $_POST['alergia'];
        $infoAdicional= $_POST['infoAdicional'];
            
        $db->editaHistorico($fumante, $usoDeAlcool, $usoDeDroga, $alergia, $infoAdicional, $idPaciente);
       
        header("Location: http://". $ip."/PhpProject2/recepcionista.php");
   
    }else if ($tag == 'respondeSolicitacao'){
        $idSolicitacao = $_POST['idSolicitacao'];
        $nome = $_POST['nome'];
        $idMedico = $_POST['idMedico'];
        $idPaciente = $_POST['idPaciente'];
        $resposta = $_POST['resposta'];
        $db->respondeSolicitacao($idSolicitacao, $resposta);
        
        $medico = $db->getMedico($idMedico);
        $ids = array();
        $ids[] = $medico['reg_id'];
        $tipo = "solicitacao";
      
        $data = array('tipo'=>$tipo, 'nomePaciente'=>$nome,'message' => $resposta, 'idPaciente'=> $idPaciente);
        $db->sendGCM($data, $ids);
        
        header("Location: http://". $ip."/PhpProject2/enfermeira.php");
        
    }else if($tag == 'registraEvolucaoPeloSite'){
         $idPaciente = $_POST['idPaciente'];
         $descricao = $_POST['descricao'];
         
         $registraEvolucao = $db->registraEvolucao($idPaciente, $descricao);
         header("Location: http://". $ip."/PhpProject2/evolucao.php");
         

        
    }else if ($tag == 'apagaUsuario'){
        $idUsuario = $_POST["idUsuario"];
        $tipoDeUsuario = $_POST['tipoDeUsuario'];
        $db->apagaUsuario($idUsuario, $tipoDeUsuario);
        header("Location: http://". $ip."/PhpProject2/admin.php");
        
    }else if ($tag == 'editaUsuario'){
        $idUsuario = $_POST["idUsuario"];
        $nome = $_POST["nome"];
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $endereco = $_POST['endereco'];
        $tipoDeUsuario = $_POST['tipoDeUsuario'];
        
        $db->editaUsuario($idUsuario, $nome, $login, $senha, $telefone, $email, $endereco, $tipoDeUsuario);
        header("Location: http://". $ip."/PhpProject2/admin.php");
        
        
    }else if ($tag =='insereMedicamento'){
        $nome = $_POST["nome"];
        $descricao = $_POST['descricao'];
        $doseMinima = $_POST['doseMinima'];
        $doseMaxima = $_POST['doseMaxima'];

        $db->insereMedicamento($nome, $descricao, $doseMaxima, $doseMinima);
        header("Location: http://". $ip."/PhpProject2/medicamentos.php");
    
        
    }else if ($tag =='apagaMedicamento'){
        $idMedicamento = $_POST["idMedicamento"];
        $db->apagaMedicamento($idMedicamento);
        header("Location: http://". $ip."/PhpProject2/medicamentos.php");
        
    }else if ($tag =='insereDiagnostico'){
        $nome = $_POST["nome"];
        $minTemperatura = (!empty($_POST['minTemperatura'])) ? $_POST["minTemperatura"] : "DEFAULT";
        $maxTemperatura = (!empty($_POST['maxTemperatura'])) ? $_POST["maxTemperatura"] : "DEFAULT";
        $minTaxaBatimentos =(!empty($_POST['minTaxaBatimentos'])) ? $_POST["minTaxaBatimentos"] : "DEFAULT";
        $maxTaxaBatimentos = (!empty($_POST['maxTaxaBatimentos'])) ? $_POST["maxTaxaBatimentos"] : "DEFAULT";
        $minGlicose= (!empty($_POST['minGlicose'])) ? $_POST["minGlicose"] : "DEFAULT";
        $maxGlicose = (!empty($_POST['maxGlicose'])) ? $_POST["maxGlicose"] : "DEFAULT";
        $minSaturacao =(!empty($_POST['minSaturacao'])) ? $_POST["minSaturacao"] : "DEFAULT";
        $maxSaturacao = (!empty($_POST['maxSaturacao'])) ? $_POST["maxSaturacao"] : "DEFAULT";
        $minSistolica = (!empty($_POST['minSistolica'])) ? $_POST["minSistolica"] : "DEFAULT";
        $maxSistolica = (!empty($_POST['maxSistolica'])) ? $_POST["maxSistolica"] : "DEFAULT";
        $minDiastolica = (!empty($_POST['minDiastolica'])) ? $_POST["minDiastolica"] : "DEFAULT";
        $maxDiastolica = (!empty($_POST['maxDiastolica'])) ? $_POST["maxDiastolica"] : "DEFAULT";
        $minFuncaoPulmonar= (!empty($_POST['minFuncaoPulmonar'])) ? $_POST["minFuncaoPulmonar"] : "DEFAULT";
        $maxFuncaoPulmonar = (!empty($_POST['maxFuncaoPulmonar'])) ? $_POST["maxFuncaoPulmonar"] : "DEFAULT";
        
        $db->insereDiagnostico($nome, $minTemperatura, $maxTemperatura, $minTaxaBatimentos, $maxTaxaBatimentos, $minGlicose, $maxGlicose, $minSaturacao, $maxSaturacao, $minSistolica, $maxSistolica, $minDiastolica, $maxDiastolica, $minFuncaoPulmonar, $maxFuncaoPulmonar);
        header("Location: http://". $ip."/PhpProject2/diagnosticos.php");
        
    }else if ($tag =='apagaDiagnostico'){
        $idDiagnostico = $_POST["idDiagnostico"];
        $db->apagaDiagnostico($idDiagnostico);
        header("Location: http://". $ip."/PhpProject2/diagnosticos.php");
        
    }else if ($tag == 'loginPeloSite'){
         $login = $_POST['login'];
        $senha = $_POST['senha'];
 
        // check for user
        $user = $db->getUsuario($login, $senha);
        if ($user){
            if ($user["tipoDeUsuario"] == 2){
                setcookie("login",serialize($user));
                
               
                header("Location: http://". $ip."/PhpProject2/enfermeira.php");
            }else if ($user["tipoDeUsuario"] == 3){
                setcookie("login",serialize($user));
                header("Location: http://". $ip."/PhpProject2/recepcionista.php");
            } else if($user["admin"] == 1){
                setcookie("login",serialize($user));
                header("Location: http://". $ip."/PhpProject2/admin.php");
            }
        }else{
            echo naoacho;
        }
        
    }else {       
        // user failed to store
        $response["erro"] = TRUE;
        $response["erro_msg"] = "'tag' desconhecida";
        echo json_encode($response);
    }
} else {
    $response["erro"] = TRUE;
    $response["erro_msg"] = "Parametro tag faltando!";
    echo json_encode($response);
}
?>


