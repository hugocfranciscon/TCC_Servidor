<?php
header('Content-Type: text/html; charset=utf-8');
class DB_Functions {
 
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    function __destruct() {         
    }
 
    /**
     * Storing new user
     * returns user details
     */
    public function salvaUsuario($nome, $login, $senha, $email, $telefone, $endereco, $tipoDeUsuario, $admin) {
        $hash = $this->hashSSHA($senha);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysql_query("INSERT INTO `mydb`.`Usuario` (`idUsuario`, `nome`, `login`, `senha_criptografada`, `salt`, `telefone`, `email`, `endereco`,  `tipoDeUsuario`, `admin`) VALUES (NULL, '$nome','$login','$encrypted_password', '$salt',  '$telefone','$email','$endereco', '$tipoDeUsuario', '$admin')");
        // check for successful store
        if ($result) {
            // get user details 
            $result = mysql_query("SELECT * FROM `mydb`.`Usuario` WHERE login = '$login'");
            // return user details
            return mysql_fetch_array($result);
        }
    }
    
    /**
     * Storing new user
     * returns user details
     */
    public function salvaMedico($especializacao, $idMedico, $tipoDeUsuario) {
        $result = mysql_query("INSERT INTO `mydb`.`Medico` (`reg_id`, `especializacao`, `Usuario_idUsuario`,`Usuario_tipoDeUsuario`) VALUES (NULL, '$especializacao','$idMedico', '$tipoDeUsuario')");
        // check for successful store
        if ($result) {
            // get user details 
            $result = mysql_query("SELECT * FROM `mydb`.`Medico` WHERE `Usuario_idUsuario`  = '$idMedico'");
            // return user details
            return mysql_fetch_array($result);
        } else {    
            return false;
        }
    }    
    
    public function salvaEnfermeira($ala, $tipoDeUsuario, $idEnfermeira) {
        $result = mysql_query("INSERT INTO `mydb`.`Enfermeira` (`Ala_idAla`, `Usuario_idUsuario`,`Usuario_tipoDeUsuario`) VALUES ('$ala', '$idEnfermeira', '$tipoDeUsuario')");
        // check for successful store
        if ($result) {
            // get user details 
            $result = mysql_query("SELECT * FROM `mydb`.`Enfermeira` WHERE `Usuario_idUsuario`  = '$idEnfermeira'");
            // return user details
            return mysql_fetch_array($result);
        } else {    
            return false;
        }
    }
    
    public function salvaRecepcionista($tipoDeUsuario, $idRecepcionista) {
        $result = mysql_query("INSERT INTO `mydb`.`Recepcionista` (`Usuario_idUsuario`,`Usuario_tipoDeUsuario`) VALUES  ('$idRecepcionista', '$tipoDeUsuario')");
        // check for successful store
        if ($result) {
            // get user details 
            $result = mysql_query("SELECT * FROM `mydb`.`Recepcionista` WHERE `Usuario_idUsuario`  = '$idRecepcionista'");
            // return user details
            return mysql_fetch_array($result);
        } else {    
            return false;
        }
    }
    
    /**
     * store regid
     * returns regId
     */
    public function storeRegId($idMedico, $reg_id) {     
        if ($reg_id === "NULL"){
            $result = mysql_query("UPDATE `mydb`.`Medico` SET reg_id = NULL WHERE Usuario_idUsuario='$idMedico'");
        } else {
            $result = mysql_query("UPDATE `mydb`.`Medico` SET reg_id='$reg_id' WHERE Usuario_idUsuario='$idMedico'");
        }
        
        // check for successful store
        if ($result) {
            // get reg_id
            $result = mysql_query("SELECT * FROM `mydb`.`Medico` WHERE Usuario_idUsuario = '$idMedico'");
            // return reg_id
            return mysql_fetch_array($result);
        }
    }
    
    /**
     * Salva Sinais de um paciente
     * @param type $idPaciente
     * @param type $temperatura
     * @param type $taxaBatimentos
     * @param type $glicose
     * @param type $saturacaoOxigenio
     * @param type $pressaoSistolica
     * @param type $pressaoDiastolica
     * @param type $funcaoPulmonar
     * @return boolean
     */
    public function salvaSinais($idPaciente, $temperatura, $taxaBatimentos, $glicose, $saturacaoOxigenio, $pressaoSistolica, $pressaoDiastolica, $funcaoPulmonar) {
        $result = mysql_query("INSERT INTO `mydb`.`Sinais` (`dataHora`, `temperatura`, `taxaBatimentos`, `glicose`, `saturacaoOxigenio`, `pressaoSistolica`, `pressaoDiastolica`, `funcaoPulmonar`, `Paciente_idPaciente`) "
                . "VALUES (NULL,'$temperatura', '$taxaBatimentos', '$glicose', '$saturacaoOxigenio', '$pressaoSistolica', '$pressaoDiastolica', '$funcaoPulmonar', '$idPaciente')"
                ." ON DUPLICATE KEY UPDATE dataHora=NULL, temperatura='$temperatura', taxaBatimentos='$taxaBatimentos', glicose='$glicose', saturacaoOxigenio='$saturacaoOxigenio', pressaoSistolica='$pressaoSistolica', pressaoDiastolica='$pressaoDiastolica', "
                ." funcaoPulmonar='$funcaoPulmonar';") or die(mysql_error());
        // check for successful store
        if ($result) {
            // pega sinais
            $result = mysql_query("SELECT temperatura,taxaBatimentos,glicose,saturacaoOxigenio,pressaoSistolica,pressaoDiastolica,funcaoPulmonar,Paciente_idPaciente FROM `mydb`.`Sinais` WHERE Paciente_idPaciente = '$idPaciente'");
            // retorna sinais
            return mysql_fetch_array($result);
        }
    }    
    
    public function salvaAlerta($idPaciente, $observacoes) {
        $result = mysql_query("INSERT INTO `mydb`.`HistóricoAlerta` (`idAlertas`, `Paciente_idPaciente`, `dataHora`, `observacoes`) VALUES (NULL, '$idPaciente', NULL, '$observacoes')");
        if ($result) {
            $idAlerta = mysql_insert_id();
            $result = mysql_query("SELECT idAlertas FROM `mydb`.`HistóricoAlertas` WHERE idAlertas = '$idAlerta'");
            return mysql_fetch_array($result);
        }
    }
    
    /**
     * 
     * @param type $idPaciente
     * @param type $temperatura
     * @param type $taxaBatimentos
     * @param type $glicose
     * @param type $saturacaoOxigenio
     * @param type $pressaoSistolica
     * @param type $pressaoDiastolica
     * @param type $funcaoPulmonar
     * @return boolean
     */
    public function verificaSinais($idPaciente, $temperatura, $taxaBatimentos, $glicose, $saturacaoOxigenio, $pressaoSistolica, $pressaoDiastolica, $funcaoPulmonar) {
        $diagnostico = mysql_query("SELECT CID_idCID FROM `mydb`.`Paciente_CID` WHERE Paciente_idPaciente = '$idPaciente'");
        $no_of_rows = mysql_num_rows($diagnostico);
        
        if ($no_of_rows > 0) {
            while($retorno = mysql_fetch_assoc($diagnostico)){
                $idCID = $retorno['CID_idCID'];
                $result = mysql_query("SELECT * FROM `mydb`.`CID` WHERE idCID = '$idCID'");
                $result = mysql_fetch_assoc($result);
                                     
                // Retorna valor fora do normal
                if (($temperatura < $result['minTemperatura']) || ($temperatura > $result['maxTemperatura'])){
                    return ("Temperatura: ". $temperatura ."°C");
                } else if (($taxaBatimentos < $result['minTaxaBatimentos']) || ($taxaBatimentos > $result['maxTaxaBatimentos'])){
                    return ("Taxa de batimentos: ".$taxaBatimentos." bpm");
                } else if (($glicose < $result['minGlicose']) || ($glicose > $result['maxGlicose'])){
                    return ("Glicose: ".$glicose . " mg/dl");
                } else if (($saturacaoOxigenio < $result['minSaturacao']) || ($saturacaoOxigenio > $result['maxSaturacao'])){
                    return ("Saturacao de Oxigenio: ".$saturacaoOxigenio . "%");
                } else if (($pressaoSistolica < $result['minSistolica']) || ($pressaoSistolica > $result['maxSistolica'])){
                    return ("Pressao Sistolica: ".$pressaoSistolica . " mmHg");
                } else if (($pressaoDiastolica < $result['minDiastolica']) || ($pressaoDiastolica > $result['maxDiastolica'])){
                    return ("Pressao Diastolica: ".$pressaoDiastolica . " mmHg");
                } else if (($funcaoPulmonar < $result['minFuncaoPulmonar']) || ($funcaoPulmonar > $result['maxFuncaoPulmonar'])){
                    return ("Funcao Pulmonar: ".$funcaoPulmonar . " vezez/minuto");
                }    
            }
            return false;
        } else {
            return false;
        }
    }
    
    public function editaPaciente($idPaciente, $nome, $dataDeNascimento, $sexo, $dataDeAdmissao, $quarto, $descricao, $endereco, $telefone, $email){
        $result = mysql_query("UPDATE `mydb`.`Paciente` SET nome='$nome', dataDeNascimento='$dataDeNascimento', sexo='$sexo',descricao='$descricao', dataDeAdmissao='$dataDeAdmissao', Quarto_idQuarto='$quarto', endereco='$endereco', telefone='$telefone', email='$email' WHERE idPaciente='$idPaciente'") or die(mysql_error());
        // check for successful store
        if ($result) {
            return true;            
        } else {
            return false;
        }        
    }
    
    public function editaUsuario($idUsuario, $nome, $login, $senha, $telefone, $email, $endereco, $tipoDeUsuario){
        $hash = $this->hashSSHA($senha);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $result = mysql_query("UPDATE `mydb`.`Usuario` SET nome='$nome', login='$login', senha_criptografada='$encrypted_password',salt='$salt', telefone='$telefone', email='$email', endereco='$endereco', tipoDeUsuario='$tipoDeUsuario' WHERE idUsuario='$idUsuario'") or die(mysql_error());
        if ($result) {
            return true;            
        } else {
            return false;
        }        
    }
    
    public function getUltimoPacienteInserido(){
        $result = mysql_query("SELECT idPaciente FROM `mydb`.`Paciente` ORDER BY idPaciente DESC LIMIT 0,1");
         if ($result) {
            $paciente = mysql_fetch_array($result);        
            return $paciente["idPaciente"];            
        } else {
            return false;
        }
    }
    
    public function editaHistorico($fumante, $usoDeAlcool, $usoDeDroga, $alergia, $infoAdicional, $idPaciente){
        $result = mysql_query("UPDATE `mydb`.`HistoricoMedico` SET fumante='$fumante', usoDeAlcool='$usoDeAlcool', usoDeDroga='$usoDeDroga',alergia='$alergia', infoAdicional='$infoAdicional' WHERE Paciente_idPaciente='$idPaciente'") or die(mysql_error());
        if ($result) {
            return true;        
        } else {
            return false;
        }
    }
    
    public function apagaPaciente($idPaciente){
        $result = mysql_query("DELETE FROM `mydb`.`Paciente` WHERE idPaciente='$idPaciente'") or die(mysql_error());
        // check for successful store
        if ($result) {
            return true;            
        } else {
            return false;
        }
    }
    
    public function apagaMedico_paciente($idPaciente){
        $result = mysql_query("DELETE FROM `mydb`.`Medico_Paciente` WHERE Paciente_idPaciente='$idPaciente'") or die(mysql_error());
        // check for successful store
        if ($result) {
            return true;            
        } else {
            return false;
        }
    }
    
    public function apagaMedicamento($idMedicamento){
        $result = mysql_query("DELETE FROM `mydb`.`Medicamento` WHERE idMedicamento='$idMedicamento'") or die(mysql_error());
        // check for successful store
        if ($result) {
            return true;            
        } else {
            return false;
        }
    }
    
    public function apagaDiagnostico($idDiagnostico){
        $result = mysql_query("DELETE FROM `mydb`.`CID` WHERE idCID='$idDiagnostico'") or die(mysql_error());
        // check for successful store
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    public function apagaUsuario($idUsuario, $tipoDeUsuario){
        if ($tipoDeUsuario == 1) {
            $result = mysql_query("DELETE FROM `mydb`.`Medico` WHERE Usuario_idUsuario='$idUsuario'") or die(mysql_error());
        }else if ($tipoDeUsuario == 2) {
            $result = mysql_query("DELETE FROM `mydb`.`Enfermeira` WHERE Usuario_idUsuario='$idUsuario'") or die(mysql_error());
        } else {
            $result = mysql_query("DELETE FROM `mydb`.`Recepcionista` WHERE Usuario_idUsuario='$idUsuario'") or die(mysql_error());
        }
        $result = mysql_query("DELETE FROM `mydb`.`Usuario` WHERE idUsuario='$idUsuario'") or die(mysql_error());
        // check for successful store
        if ($result) {
            return true;            
        } else {
            return false;
        }        
    }
    
    public function inserePaciente($nome, $dataDeNascimento, $sexo, $quarto, $descricao, $endereco, $telefone, $email, $fumante, $usoDeAlcool, $usoDeDroga, $alergia, $infoAdicional){
        $result = mysql_query("INSERT INTO `mydb`.`Paciente` (`idPaciente`, `nome`, `dataDeNascimento`, `sexo`, `descricao`, `endereco`, `telefone`, `email`, `dataDeAdmissao`, `Quarto_idQuarto`) VALUES (NULL, '$nome', '$dataDeNascimento', '$sexo', '$descricao', '$endereco', '$telefone', '$email', NULL, '$quarto');") or die(mysql_error());
        // check for successful store
        $idPaciente = mysql_insert_id();
        $result2 = mysql_query("INSERT INTO `mydb`.`HistoricoMedico` (`fumante`, `usoDeAlcool`, `usoDeDroga`, `alergia`, `infoAdicional`, `Paciente_idPaciente`) VALUES ('$fumante', '$usoDeAlcool', '$usoDeDroga', '$alergia', '$infoAdicional', '$idPaciente');") or die(mysql_error());

        if ($result2 && $result) {
            $result = mysql_query("SELECT * FROM `mydb`.`Paciente` WHERE idPaciente='$idPaciente'");            
            return mysql_fetch_assoc($result);            
        } else {
            return false;
        }        
    }
    
    public function insereMedicamento($nome, $descricao, $doseMaxima, $doseMinima){
        $result = mysql_query("INSERT INTO `mydb`.`Medicamento` (`idMedicamento`, `nome`, `descricao`, `doseMaxima`, `doseMinima`) VALUES (NULL, '$nome', '$descricao', '$doseMaxima', '$doseMinima');") or die(mysql_error());
        if ($result) {
            return true;            
        } else {
            return false;
        }        
    }
    
    public function insereDiagnostico($nome, $minTemperatura, $maxTemperatura, $minTaxaBatimentos, $maxTaxaBatimentos, $minGlicose, $maxGlicose, $minSaturacao, $maxSaturacao, $minSistolica, $maxSistolica, $minDiastolica, $maxDiastolica, $minFuncaoPulmonar, $maxFuncaoPulmonar){
        $result = mysql_query("INSERT INTO `mydb`.`CID` (`idCID`, `nome`, `minTemperatura`, `maxTemperatura`, `minTaxaBatimentos`, `maxTaxaBatimentos`, `minGlicose`, `maxGlicose`, `minSaturacao`, `maxSaturacao`, `minSistolica`, `maxSistolica`, `minDiastolica`, `maxDiastolica`, `minFuncaoPulmonar`, `maxFuncaoPulmonar`) VALUES (NULL, '$nome' , $minTemperatura, $maxTemperatura, $minTaxaBatimentos, $maxTaxaBatimentos, $minGlicose, $maxGlicose, $minSaturacao, $maxSaturacao, $minSistolica, $maxSistolica, $minDiastolica, $maxDiastolica, $minFuncaoPulmonar, $maxFuncaoPulmonar);") or die(mysql_error());
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getMedicosDoPaciente($idPaciente) {
        $result = mysql_query("SELECT * FROM `mydb`.`Medico_Paciente` WHERE Paciente_idPaciente = '$idPaciente'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function enviaAlerta($idPaciente, $mensagem, $tipo){
        // Cria array de reg_ids com o reg_ids de todos os medicos associados ao paciente
        $ids = array();
        // Mensagem para envio
        $paciente = $this->getPaciente($idPaciente);
        $data = array('tipo'=>$tipo, 'nomePaciente'=>$paciente['nome'],'message' => $mensagem, 'idPaciente'=> $idPaciente);     
        
        // Seleciona Medicos associados ao paciente em questao
        $result = mysql_query("SELECT Medico_Usuario_idUsuario FROM `mydb`.`Medico_Paciente` WHERE Paciente_idPaciente = '$idPaciente'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        
        if ($no_of_rows > 0) {
            while($result2 = mysql_fetch_assoc($result)){    // Percorre os medicos associados ao paciente
                //Pega o id do medico
                $idMedico = $result2['Medico_Usuario_idUsuario'];
                
                // Pega reg_Id do medico
                $result3= mysql_query("SELECT reg_id FROM `mydb`.`Medico` WHERE Usuario_idUsuario = '$idMedico'") or die(mysql_error());
                $no_of_rows = mysql_num_rows($result3);
                if ($no_of_rows > 0) {
                    $result4=mysql_fetch_array($result3);
                    // Adiciona reg_id ao array                    
                    $reg_id = null;
                    $reg_id = $result4['reg_id'];
                    if ($reg_id !== null) {
                        $ids[] = $reg_id; 
                    } 
                }
            }          
            $this->sendGCM($data, $ids); //this ta certo ????????
            return true;
        } else {
            return false;
        }    
    }    
      
    public function sendGCM($data, $ids)
    {
        $apiKey = 'AIzaSyBsK5GWHr9ddQH2x7IV-9QI3Uj3AVN-z4s';
        $url = 'https://android.googleapis.com/gcm/send'; // GCM endpoint
        $post = array('registration_ids'=>$ids, 'data'=>$data);
        $headers = array('Authorization: key='.$apiKey, 'Content-Type: application/json');
        // Initialize curl handle
        $ch = curl_init();  
        // Set URL to GCM endpoint
        curl_setopt($ch, CURLOPT_URL, $url);
        // Set request method to POST
        curl_setopt($ch, CURLOPT_POST, true);
        // Set our custom headers---
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Get the response back as 
        // string instead of printing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        // Set post data as JSON-
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));        
        // Actually send the push!
        $result = curl_exec($ch);
        // Close curl handle
        curl_close($ch);
        // Debug GCM response
        echo $result;        
    }    
    
    /**
     * Check user is existed or not
     */
    public function isUserExisted($login) {
        $result = mysql_query("SELECT login from `mydb`.`Usuario` WHERE login = '$login'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }
 
    /**
     * Get user by email and password
     */
    public function getUsuario($login, $senha) {
        $result = mysql_query("SELECT * FROM `mydb`.`Usuario` WHERE login = '$login'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $usuario = mysql_fetch_array($result);
            $salt = $usuario['salt'];
            $encrypted_password = $usuario['senha_criptografada'];
            $hash = $this->checkhashSSHA($salt, $senha);
            // check for password equality
            if ($encrypted_password == $hash) {
                return $usuario;
            } else {
                return false;
            }
        } else {
            // user not found
            return false;
        }
    }
    
    public function getTodosUsuarios(){
        $result = mysql_query("SELECT * FROM `mydb`.`Usuario`") or die(mysql_error());
        return $result;
    }

    /**
     * Obtém todos os pacientes associados ao médico
     * @param type $idMedico
     */
    public function getPacientes($idMedico){
        $result = mysql_query("SELECT Paciente_idPaciente FROM `mydb`.`Medico_Paciente` WHERE Medico_Usuario_idUsuario = '$idMedico'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($retorno = mysql_fetch_assoc($result)){
                //Pega o id do paciente
                $idPaciente = $retorno['Paciente_idPaciente'];                
                // Pega as informações do paciente
                $result2= mysql_query("SELECT idPaciente, nome FROM `mydb`.`Paciente` WHERE idPaciente = '$idPaciente'") or die(mysql_error());
                // $result2 = $this->getPaciente($idPaciente);
                
                $retorno2 = mysql_fetch_assoc($result2);                    
                $output[$retorno2{'idPaciente'}]= $retorno2;
            }            
            return $output;           
        } else {
            // Nao possui pacientes
            return false;
        }
    }
    
    public function getTodosPacientes(){
        $result = mysql_query("SELECT * FROM `mydb`.`Paciente`") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }
    
    public function getPaciente($idPaciente){
        $paciente = mysql_query("SELECT nome, dataDeNascimento,descricao,Quarto_idQuarto, dataDeAdmissao FROM `mydb`.`Paciente` WHERE idPaciente = '$idPaciente'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($paciente);
        if ($no_of_rows > 0) {
            $paciente = mysql_fetch_array($paciente);
            $result["nome"] = $paciente["nome"];
            $result["dataDeNascimento"] = $paciente["dataDeNascimento"];
            $result["descricao"] = $paciente["descricao"];
            $result["Quarto_idQuarto"] = $paciente["Quarto_idQuarto"];
            $result["dataDeAdmissao"] = $paciente["dataDeAdmissao"];
            return $result;
        } else {
            return false;
        }  
    }
    
    public function getEnfermeira($idEnfermeira) {        
        $result = mysql_query("SELECT * FROM `mydb`.`Enfermeira` WHERE Usuario_idUsuario='$idEnfermeira'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            return mysql_fetch_assoc($result);
        } else {
            return false;
        }
    }
    
    public function getInfoUsuario($idUsuario) {        
        $result = mysql_query("SELECT * FROM `mydb`.`Usuario` WHERE idUsuario='$idUsuario'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_assoc($result);
            return $result;
        } else {
            return false;
        }
    }
    
    public function getMedico($idMedico){        
        $result = mysql_query("SELECT * FROM `mydb`.`Medico` WHERE Usuario_idUsuario='$idMedico'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_assoc($result);
            return $result;
        } else {
            return false;
        }
    }
    
    public function medico_paciente($medicos, $idPaciente){
        foreach ($medicos as $medico){
            $result = mysql_query("INSERT INTO `mydb`.`Medico_Paciente` (`Paciente_idPaciente`, `Medico_Usuario_idUsuario`) VALUES ('$idPaciente', '$medico')") or die(mysql_error());
        }
    }
    
    public function getMedicos(){        
        $result = mysql_query("SELECT * FROM `mydb`.`Medico`") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) { 
            return $result;
        } else {
            return false;     
        }
    }
    
    public function getQuarto($idQuarto){
        $result = mysql_query("SELECT * FROM `mydb`.`Quarto` WHERE idQuarto = '$idQuarto'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }

    public function getSinais($idPaciente){
        $result = mysql_query("SELECT * FROM `mydb`.`Sinais` WHERE Paciente_idPaciente = '$idPaciente' ORDER By dataHora DESC LIMIT 1") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    
    public function getHistorico($idPaciente){
        $result = mysql_query("SELECT * FROM `mydb`.`HistoricoMedico` WHERE Paciente_idPaciente = '$idPaciente'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    
    
    public function getMedicamentos(){
        $output = array();
        $result = mysql_query("SELECT * FROM `mydb`.`Medicamento`") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($e = mysql_fetch_assoc($result)){
                $idMedicamento = $e["idMedicamento"];
                $output[$idMedicamento]= $e; 
            }     
            return $output;
        } else {
            return false;
        }
    }
    
    public function getMedicamentoPorId($idMedicamento){
        $result = mysql_query("SELECT * FROM `mydb`.`Medicamento` WHERE idMedicamento='$idMedicamento'") or die(mysql_error());
        return mysql_fetch_assoc($result);
    }
    
    public function getQuartosDaAla($ala){
        $result = mysql_query("SELECT idQuarto FROM `mydb`.`Quarto` WHERE Ala_idAla = '$ala'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        
        if ($no_of_rows > 0) {    
            return $result;
        } else {
            return false;
        }
    }    
    
    public function getPacientesPorAla($ala){
        $output = array();
        $quartos = $this->getQuartosDaAla($ala);         
        $no_of_rows = mysql_num_rows($quartos);        
        if ($no_of_rows>0){
            while($retorno = mysql_fetch_assoc($quartos)){
                $idQuarto = $retorno["idQuarto"];
                $result = mysql_query("SELECT idPaciente FROM `mydb`.`Paciente` WHERE Quarto_idQuarto = '$idQuarto' ") or die(mysql_error());
                $paciente = mysql_fetch_assoc($result);
                $output[] = $paciente["idPaciente"];
            }
        }
        return $output;
    }

    public function getSolicitacoes($ala){
        $pacientes = $this->getPacientesPorAla($ala);
        $solicitacoes = array();        
        foreach ($pacientes as $idPaciente){            
            $result = mysql_query("SELECT * FROM `mydb`.`Solicitacao` WHERE (confirmacao IS NULL AND Paciente_idPaciente='$idPaciente')") or die(mysql_error());
            
            if (mysql_num_rows($result)>0){
                while ($row = mysql_fetch_assoc($result)){                      
                    $solicitacoes[] = $row; 
                }
            }
        }
        return $solicitacoes;
    }
    
    public function getDiagnosticos(){
        $output = array();
        $result = mysql_query("SELECT * FROM `mydb`.`CID`") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($e = mysql_fetch_assoc($result)){
                $idCID = $e["idCID"];
                $output[$idCID]= $e; 
            }
            return $output;
        } else {
            return false;
        }
    }
    
     public function getDiagnosticoDoPaciente($idPaciente){
         $output = array();
        $result = mysql_query("SELECT CID_idCID FROM `mydb`.`Paciente_CID` WHERE Paciente_idPaciente = '$idPaciente'") or die(mysql_error());
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            while($e = mysql_fetch_assoc($result)){
                //Pega o id do CID
                $idCID = $e['CID_idCID'];                
                // Pega nome do diagnostico
                $result2= mysql_query("SELECT nome FROM `mydb`.`CID` WHERE idCID = '$idCID'") or die(mysql_error());
                $e2=mysql_fetch_assoc($result2);
                
                $output[$idCID]= $e2;        
            }  
           return $output;
        } else {
            return false;
        }
    }
    
    public function registraEvolucao($idPaciente, $descricao){
        $result = mysql_query("INSERT INTO `mydb`.`Evolucao` (`idEvolucao`, `Paciente_idPaciente`, `data`, `descricao`) VALUES (NULL, '$idPaciente',NULL, '$descricao');") or die(mysql_error());
        if ($result) {
            $idEvolucao = mysql_insert_id();
            $result = mysql_query("SELECT idEvolucao, data FROM `mydb`.`Evolucao` WHERE idEvolucao = '$idEvolucao'");
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
    
    public function registraDiagnostico($idPaciente, $idDiagnostico, $observacoes){
        $result = mysql_query("INSERT INTO `mydb`.`Paciente_CID` (`Paciente_idPaciente`, `CID_idCID`, `observacoes`, `data`) VALUES ('$idPaciente','$idDiagnostico', '$observacoes', NULL);") or die(mysql_error());
        if ($result) {
            $result = mysql_query("SELECT Paciente_idPaciente, CID_idCID, data FROM `mydb`.`Paciente_CID` WHERE CID_idCID = '$idDiagnostico' AND Paciente_idPaciente = '$idPaciente'");
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
    
    public function registraRequisicao($idPaciente, $idMedico, $idMedicamento, $descricao, $dose){
       
         if ($idMedicamento != "NULL"){
            $result = mysql_query("INSERT INTO `mydb`.`Solicitacao` (`idSolicitacao`,`Paciente_idPaciente`, `Medicamento_idMedicamento`, `descricao`,`data`, `doseAdministrada`, `resposta`,`confirmacao`,  `Medico_Usuario_idUsuario`) VALUES (NULL, '$idPaciente', '$idMedicamento', '$descricao', NULL, '$dose', NULL, NULL, '$idMedico')") or die(mysql_error());
        } else {
            $result = mysql_query("INSERT INTO `mydb`.`Solicitacao` SET Paciente_idPaciente='$idPaciente', descricao='$descricao', Medico_Usuario_idUsuario='$idMedico'") or die(mysql_error());
        }
        if ($result) {
            $idRequisicao = mysql_insert_id();
            $result = mysql_query("SELECT idSolicitacao, data FROM `mydb`.`Solicitacao` WHERE idSolicitacao = '$idRequisicao'");
            
            return mysql_fetch_array($result);            
        } else {
            return false;
        }
    }
    
    public function respondeSolicitacao($idSolicitacao, $resposta){
        $result = mysql_query("UPDATE `mydb`.`Solicitacao` SET resposta='$resposta', confirmacao=TRUE WHERE idSolicitacao='$idSolicitacao'");
    }

    /**
     * Criptografia da senha
     */
    public function hashSSHA($password) { 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
    
    /**
     * Descriptografia da senha
     */
    public function checkhashSSHA($salt, $password) { 
        $hash = base64_encode(sha1($password . $salt, true) . $salt); 
        return $hash;
    } 
} 
?>
