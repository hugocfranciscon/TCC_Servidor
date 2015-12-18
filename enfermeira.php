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
        <meta http-equiv="refresh" content="25" />
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
        <title>Enfermeira</title>
        

        
       
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
                        <li class="active"><a href="#">Solicitações <span class="sr-only">(current)</span></a></li>
                        <li><a href="evolucao.php">Evolução</a></li>               
                       
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
        
        if ($usuario["tipoDeUsuario"] == 2 ){
            require_once 'include/DB_Functions.php';
            $db = new DB_Functions();

            $enfermeira = $db->getEnfermeira($usuario["idUsuario"]);
            $solicitacoes = $db->getSolicitacoes($enfermeira["Ala_idAla"]);
        }else{
            echo" Bem-Vindo, convidado <br>";
            echo" Essas informações <font color='red'>NÃO PODEM</font> ser acessadas por você";
            echo"<br><a href='login.html'> Faça Login</a> como enfermeira para ler o conteúdo<br><br><br><br>";
            exit();
        }
            
         /* function getUltimoPacienteId(){
              require_once 'include/DB_Functions.php';
               $db = new DB_Functions();
            
              $id = $db->getUltimoPacienteInserido();
              return $id;
          }*/
        
        ?>

      
        <div class="table-responsive">
        <div class="table" border="2"  >
            <div class="tableHead">
                
                    <div class="cell">#ID</div>
                    <div class="cell">Paciente</div>
                    <div class="cell">Medicamento</div> 
                    <div class="cell">Descrição</div>
                    <div class="cell">Quarto</div>
                    <div class="cell">Dose(mg)</div>
                    <div class="cell">Data</div>  
                    <div class="cell">Resposta</div>   
                    <div class="cell">Confirmação</div> 
            </div>
            
    
                <?php
                    
 
                    foreach ($solicitacoes as $row){
                      $formName = "solicitacao" . $row["idSolicitacao"];
                      $paciente = $db->getPaciente($row["Paciente_idPaciente"]);
                      $medicamento = $db->getMedicamentoPorId($row["Medicamento_idMedicamento"]);
                      
                      echo "<form class='tableRow' name=$formName id=$formName action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text' readonly name='idSolicitacao' id='idSolicitacao' value='{$row['idSolicitacao']}'></div>"
                        . "<div class='cell'><input class='id' name='nome' id='nome' readonly type='text' contentEditable=true value='{$paciente['nome']}'></div>"
                        . "<div class='cell'><input class='id' id='Medicamento' name='Medicamento' readonly type='date' contentEditable=true value='{$medicamento['nome']}'></div>"                    
                         . "<div class='cell'><input class='id' id='descricao' name='descricao' readonly type='text' contentEditable=true value='{$row['descricao']}'></div> " .
                          "<div class='cell'><input class='id' id='quarto' name='quarto' readonly type='number' contentEditable=true value='{$paciente['Quarto_idQuarto']}'></div> " . 
                          "<div class='cell'><input class='id' id='dose' name='dose' readonly value='{$row['doseAdministrada']}'></div> " 
                        . "<div class='cell'><input class='id' id='data' name='data' readonly type='text' contentEditable=true value='{$row['data']}'></div>"
                        . "<div class='cell'><input class='input2' id='resposta' name='resposta'  type='text' contentEditable=true value=''></div>" 
                        . "<input class='cell' style = 'display:none' type='text' name='idMedico' id='idMedico' value='{$row['Medico_Usuario_idUsuario']}'/>" 
                        . "<input class='cell' style = 'display:none' type='text' name='idPaciente' id='idPaciente' value='{$row['Paciente_idPaciente']}'/>" 
                        . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='respondeSolicitacao'/>" .
                          "<div class='cell'>"
                                  .  "<input type='button' class='btn btn-xs btn-success' name='popup' id='btnDialog' value='Ok' onclick='botaoConfirma_clickHandler($formName);' >"
                         . "</div> " 
                          
                            
                                
                      ."</form>"
                      
                      . "\n";
                    }
                    

                   
                   
                    
                ?>
            

            
        </div>
        </div>
            
           

                
            
           
        
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
   
        <script>
            function botaoConfirma_clickHandler(form) {
                     form.submit();
            }
            function logout(){
                document.cookie = "login" + '=; Max-Age=0';
               window.location = "index.html"; // TO REFRESH THE PAGE
    
            } 
          
            
        </script>
    </body>
</html>




