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
        <title>Evolução</title>
        

        
        
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
                        <li ><a href="enfermeira.php">Solicitações</a></li>             
                        <li class="active"><a href="#">Evolução <span class="sr-only">(current)</span></a></li>
           
                 
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Pesquisar">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a onclick="logout();" >Log out </a></li>
                
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

            $pacientes = $db->getTodosPacientes();
        }else{
            echo" Bem-Vindo, convidado <br>";
            echo" Essas informações <font color='red'>NÃO PODEM</font> ser acessadas por você";
            echo"<br><a href='login.html'> Faça Login</a> como enfermeira para ler o conteúdo<br><br><br><br>";
            exit();
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
                    <div class="cell">Ações</div>
                
            </div>
            
    
                <?php
                    $inserePaciente = "inserePaciente";
                    $insereHistorico = "insereHistorico";
  
                    while( $row = mysql_fetch_assoc( $pacientes ) ){
                     
                        $formName = "form" . $row['idPaciente'];
                        $formEvolucaoName = "evolucao". $row['idPaciente'];
                        
                    
                      
                      echo "<form class='tableRow' name=$formName id=$formName action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idPaciente' id='idPaciente' value='{$row['idPaciente']}'/></div>"
                        . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value='{$row['nome']}'/></div>"
                        . "<div class='cell'><input class='input2' id='dataDeNascimento' name='dataDeNascimento' type='date' contentEditable=true value='{$row['dataDeNascimento']}'></div>"
                        . "<div class='cell'><input class='input2' id='sexo' name='sexo' type='text' contentEditable=true value='{$row['sexo']}'></div> " .
                          "<div class='cell'><input class='input2' id='descricao' name='descricao' type='text' contentEditable=true value='{$row['descricao']}'></div> " .
                          "<div class='cell'><input class='input2' id='quarto' name='quarto' type='number' contentEditable=true value='{$row['Quarto_idQuarto']}'></div> " . 
                          "<div class='cell'><input class='input2' id='dataDeAdmissao' name='dataDeAdmissao' value='{$row['dataDeAdmissao']}'></div> " 
                         . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='tag'/>" .
                          "<div class='cell'>"
                                  .  "<input type='button' class='btn btn-xs btn-default' name='popup' id='btnDialog' value='+' onclick='toggle_visibility($formEvolucaoName);' >"
                            
                         . "</div> " 
                          
                            
                                
                      ."</form>"
                       . "\n\n"
                     
                      ."<form name=$formEvolucaoName id=$formEvolucaoName class='popupBoxOnePosition'  action='index.php' method='post'>"
                                             
                                    ."<div class='popupBoxWrapper'>"
                                            ."<div class='popupBoxContent'>" 
                                                    ."<h4>Registrar Evolução para {$row['nome']}</h4>"    
                                                       ."<input style = 'display:none' type='text' name='tag' id='tag' value='tag'/>"
                                                        ."<input style = 'display:none' type='text' name='idPaciente' id='idPaciente' value='{$row['idPaciente']}'/>"
                                                      ."<div><label for='descricao'>Descrição: </label><input style='width:200px; height:100px;'type='text' name='descricao' id='descricao' placeholder='Descrição da evolução clínica do paciente.'/></div>" 
                                                      ."<div><input type='button' class='btn btn-xs btn-success' name='popupEdita' id='popupEdita' value='Salvar' onclick='botaoRegistraEvolucao_clickHandler($formEvolucaoName);' >"
                                                    .  "<input type='button' class='btn btn-xs btn-danger' name='popupSair' id='popupSair' value='Cancela' onclick='toggle_visibility($formEvolucaoName);' ></div>"
                                            ."</div>" 
                                    ."</div>"

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
         
            
           function toggle_visibility(popUp) {
                if(popUp.style.display === 'block')
                   popUp.style.display = 'none';
                else
                   popUp.style.display = 'block';
             }
                            
            function botaoRegistraEvolucao_clickHandler(form){
               form.elements["tag"].value = "registraEvolucaoPeloSite";
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




