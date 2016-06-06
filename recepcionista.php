<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

    <head>
        <meta charset="UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="refresh" content="5" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/myStyle.css" rel="stylesheet">
      

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <title>Recepcionista</title>
        

        
      
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="index.html">Início</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Pacientes <span class="sr-only">(current)</span></a></li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Pesquisar">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a onclick="logout();">Log out</a></li>        
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <?php
            // include db handler
            $usuario = unserialize($_COOKIE["login"]);
            if ($usuario["tipoDeUsuario"] == 3 ){
                require_once 'include/DB_Functions.php';
                $database = new DB_Functions();
                $pacientes = $database->getTodosPacientes();
            } else {
                echo"Bem-Vindo, convidado <br>";
                echo"Essas informações <font color='red'>NÃO PODEM</font> ser acessadas por você";
                echo"<br><a href='login.html'>Faça Login</a> para ler o conteúdo<br><br><br><br>";
                exit();
            }
            function getUltimoPacienteId(){
                require_once 'include/DB_Functions.php';
                $database = new DB_Functions();
                return $database->getUltimoPacienteInserido();
            }
        ?>
        <div class="table-responsive">
        <div class="table" border="2"  >
            <div class="tableHead">
                
                    <div class="cell">#ID</div>
                    <div class="cell">Nome</div>
                    <div class="cell">Data de Nasc.</div>
                    <div class="cell">Sexo</div>
                    <div class="cell">Descrição</div>
                    <div class="cell">Quarto</div>
                    <div class="cell">Admissão</div>
                    <div class="cell" style="width:105px;">Médicos</div>
                    <div class="cell">Endereço</div>
                    <div class="cell">Telefone</div>
                    <div class="cell">Email</div>
                    <div class="cell">Ações</div>
                
            </div>
            
    
                <?php
                    $inserePaciente = "inserePaciente";
                    $insereHistorico = "insereHistorico";
                    
                    echo "<form class='tableRow' name=$inserePaciente id=$inserePaciente action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idPaciente' id='idPaciente' value=''/></div>"
                        . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value=''/></div>"
                        . "<div class='cell'><input class='input2' id='dataDeNascimento' name='dataDeNascimento' type='date' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='sexo' name='sexo' type='text' contentEditable=true value=''></div> " 
                        . "<div class='cell'><input class='input2' id='descricao' name='descricao' type='text' contentEditable=true value=''></div> " 
                        . "<div class='cell'><input class='input2' id='quarto' name='quarto' type='number' contentEditable=true value=''></div> "  
                        . "<div class='cell'><input class='input2' id='dataDeAdmissao' name='dataDeAdmissao' value=''></div> " 
                        . "<div class='cell2' style='width:105px;'><div class='id' style='width:105px;'><select style='width:105px;' class='medicos' id='medicos' name='medicos[]' multiple='multiple' style = 'width:100%'>";
                                $medicos = $db->getMedicos();
                                while( $row = mysql_fetch_assoc( $medicos ) ){
                                    $medico = $db->getInfoUsuario($row['Usuario_idUsuario']);
                                    echo  " <option value='{$row['Usuario_idUsuario']}'>{$medico['nome']}</option>";
                                }

                                echo "</select></div></div>"
                        . "<div class='cell'><input class='input2' id='endereco' name='endereco' type='text' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='telefone' name='telefone' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='email' name='email' type='text' contentEditable=true value=''></div>"
                        . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='inserePaciente'/>" 
                        . "<div class='cell'>"
                                  . "<input type='button' class='btn btn-xs btn-success' name='botaoSalva' id='botaoSalva' value='Salvar' onclick='botaoInserePaciente_clickHandler($insereHistorico);'>"          
                                  
                        . "</div> " 
                        .  "<div name=$insereHistorico id=$insereHistorico class='popupBoxOnePosition'  action='index.php' method='post'>"
                                             
                                    ."<div class='popupBoxWrapper'>"
                                            ."<div class='popupBoxContent'>" 
                                                    ."<h4>Histórico Médico</h4>"
                                                    ."<div><label for='fumante'>Fumante: </label><input type='checkbox' name='fumante' id='fumante'/></div>"
                                                    ."<div><label for='usoDeDroga'>Uso de Droga: </label><input type='checkbox' name='usoDeDroga' id='usoDeDroga' /></div>" 
                                                      ."<div><label for='usoDeAlcool'>Uso de Àlcool: </label><input type='checkbox' name='usoDeAlcool' id='usoDeAlcool' /></div>" 
                                                      ."<div><label for='alergia'>Alergia: </label><input type='text' style='width:200px; height:100px;' name='alergia' id='alergia' value='' /></div>" 
                                                    
                                                      ."<div><label for='infoAdicional'>Info Adicional: </label><input style='width:200px; height:100px;'type='text' name='infoAdicional' id='infoAdicional' value=''/></div><br><br>" 
                                                      ."<div><input type='button' class='btn btn-xs btn-success' name='popupEdita' id='popupEdita' value='Salvar' onclick='botaoInsereHistorico_clickHandler($inserePaciente, $insereHistorico);' >"
                                                    .  "<input type='button' class='btn btn-xs btn-danger' name='popupSair' id='popupSair' value='Cancela' onclick='toggle_visibility($insereHistorico);' ></div>"
                                            ."</div>" 
                                    ."</div>"

                       ."</div>"
                      ."</form>"
                               
                      . "\n";
                    
                    
                    
                   
                    
                    while( $row = mysql_fetch_assoc( $pacientes ) ){
                        $historico = $db->getHistorico($row['idPaciente']);
                        $formName = "form" . $row['idPaciente'];
                        $formHistoricoName = "historico". $row['idPaciente'];
                        $popUpHistoricoName = "popUp". $row['idPaciente'];
                        
                        $medicosDoPaciente = $db->getMedicosDoPaciente($row['idPaciente']);
                        $arrayMedicos = array();
                        while($med = mysql_fetch_assoc($medicosDoPaciente)){
                            $arrayMedicos[] = $med["Medico_Usuario_idUsuario"];  
                        }

                     
                       
                    
                      
                      echo "<form class='tableRow' name=$formName id=$formName action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idPaciente' id='idPaciente' value='{$row['idPaciente']}'/></div>"
                        . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value='{$row['nome']}'/></div>"
                        . "<div class='cell'><input class='input2' id='dataDeNascimento' name='dataDeNascimento' type='date' contentEditable=true value='{$row['dataDeNascimento']}'></div>"
                        . "<div class='cell'><input class='input2' id='sexo' name='sexo' type='text' contentEditable=true value='{$row['sexo']}'></div> " .
                          "<div class='cell'><input class='input2' id='descricao' name='descricao' type='text' contentEditable=true value='{$row['descricao']}'></div> " .
                          "<div class='cell'><input class='input2' id='quarto' name='quarto' type='number' contentEditable=true value='{$row['Quarto_idQuarto']}'></div> " . 
                          "<div class='cell'><input class='input2' id='dataDeAdmissao' name='dataDeAdmissao' value='{$row['dataDeAdmissao']}'></div> " 
                        . "<div class='cell2' style='min-width:150px;'><div class='id' style='width:65px;'> <select style='width:65px;' class='medicos' id='medicos2' name='medicos[]' multiple='multiple' style = 'width:100%'>";
                                $medicos = $db->getMedicos();
                                while( $row2 = mysql_fetch_assoc( $medicos ) ){
                                    $medico = $db->getInfoUsuario($row2['Usuario_idUsuario']);         
                                    echo  " <option value='{$row2['Usuario_idUsuario']}' "; if(in_array($row2['Usuario_idUsuario'],$arrayMedicos)) { echo 'selected';} else{echo '';} echo ">{$medico['nome']}</option>";
                                }

                                echo "</select></div></div>"  
                        . "<div class='cell'><input class='input2' id='endereco' name='endereco' type='text' contentEditable=true value='{$row['endereco']}'></div>"
                        . "<div class='cell'><input class='input2' id='telefone' name='telefone' type='number' contentEditable=true value='{$row['telefone']}'></div>"
                        . "<div class='cell'><input class='input2' id='email' name='email' type='text' contentEditable=true value='{$row['email']}'></div>".
                          "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='tag'/>" .
                          "<div class='cell'>"
                                  .  "<input type='button' class='btn btn-xs btn-default' name='popup' id='btnDialog' value='+' onclick='toggle_visibility($formHistoricoName);' >"
                                  . "<input type='button' class='btn btn-xs btn-success' name='botaoEdita' id='botaoEdita' value='Salvar' onclick='botaoEditaPaciente_clickHandler(event, $formName);' >"          
                                  . "<input type='button' class='btn btn-xs btn-danger' value='x' name='botaoApaga' id='botaoApaga' onclick='botaoApagaPaciente_clickHandler(event,$formName);' >" 
                         . "</div> " 
                          
                            
                                
                      ."</form>"
                       . "\n\n"
                     
                      ."<form name=$formHistoricoName id=$formHistoricoName class='popupBoxOnePosition'  action='index.php' method='post'>"
                                             
                                    ."<div class='popupBoxWrapper'>"
                                            ."<div class='popupBoxContent'>" 
                                                    ."<h4>Histórico Médico de {$row['nome']}</h4>"
                                                    ."<div><label for='fumante'>Fumante: </label><input type='checkbox' name='fumante' id='fumante'"; echo ($historico['fumante']==1 ? 'checked' :''); echo "/></div>"
                                                    ."<div><label for='usoDeDroga'>Uso de Droga: </label><input type='checkbox' name='usoDeDroga' id='usoDeDroga' "; echo ($historico['usoDeDroga']==1 ? 'checked' :''); echo "/></div>" 
                                                      ."<div><label for='usoDeAlcool'>Uso de Àlcool: </label><input type='checkbox' name='usoDeAlcool' id='usoDeAlcool' "; echo ($historico['usoDeAlcool']==1 ? 'checked' :''); echo "/></div>" 
                                                      ."<div><label for='alergia'>Alergia: </label><input type='text' style='width:200px; height:100px;' name='alergia' id='alergia' value='{$historico['alergia']}' /></div>" 
                                                       ."<input style = 'display:none' type='text' name='tag' id='tag' value='tag'/>"
                                                        ."<input style = 'display:none' type='text' name='idPaciente' id='idPaciente' value='{$row['idPaciente']}'/>"
                                                      ."<div><label for='infoAdicional'>Info Adicional: </label><input style='width:200px; height:100px;'type='text' name='infoAdicional' id='infoAdicional' value='{$historico['infoAdicional']}'/></div><br><br>" 
                                                      ."<div><input type='button' class='btn btn-xs btn-success' name='popupEdita' id='popupEdita' value='Salvar' onclick='botaoEditaHistorico_clickHandler($formHistoricoName);' >"
                                                    .  "<input type='button' class='btn btn-xs btn-danger' name='popupSair' id='popupSair' value='Cancela' onclick='toggle_visibility($formHistoricoName);' ></div>"
                                            ."</div>" 
                                    ."</div>"

                       ."</form>"
                               
                      . "\n";
                    }
                    

                   
                   
                    
                ?>
            

            
        </div>
        </div>
            
           

                
            
           
      <script src="js/bootstrap.min.js"></script>
        
 
   
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.3/js/bootstrap.min.js"></script>



<link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
    rel="stylesheet" type="text/css" />

<script src="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"
    type="text/javascript"></script>
    
<script type="text/javascript">
    /*$(function () {
        $('#medicos').multiselect({
            includeSelectAllOption: true
        });
    });*/
    
    $(function () {
        $('.medicos').multiselect({
            includeSelectAllOption: true
        });
    });

            function botaoEditaPaciente_clickHandler(event, form) {
                     form.elements["tag"].value = "editaPaciente";
                     form.submit();
            }
            
            function botaoApagaPaciente_clickHandler(event, form) {
                     form.elements["tag"].value = "apagaPaciente";
                     form.submit();
            }
            
            
            function botaoInserePaciente_clickHandler(popUp) {
                   
                     if(popUp.style.display === 'block')
                        popUp.style.display = 'none';
                     else
                        popUp.style.display = 'block';
            
            }
            
            function botaoInsereHistorico_clickHandler(form, popUp) {
                   
                     form.elements["tag"].value = "inserePaciente";
                     form.submit();
                
                     if(popUp.style.display === 'block')
                        popUp.style.display = 'none';
                     else
                        popUp.style.display = 'block';
            }
                   
            
           function toggle_visibility(popUp) {
                if(popUp.style.display === 'block')
                   popUp.style.display = 'none';
                else
                   popUp.style.display = 'block';
             }
                            
            function botaoEditaHistorico_clickHandler(form){
               form.elements["tag"].value = "editaHistorico";
               form.submit();
                
                
                if(form.style.display === 'block')
                    form.style.display = 'none';
                else
                    form.style.display = 'block';
                
           
            }
            
            function logout(){
                document.cookie = "login" + '=; Max-Age=0';
               window.location = "index.html"; // TO REFRESH THE PAGE
    
            }
            
        </script>
    </body>
</html>




