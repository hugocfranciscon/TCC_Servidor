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
        <title>Medicamentos-Admin</title>
        

        
       
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
                        <li ><a href="admin.php">Usuários </a></li>
                        <li class="active" ><a  href="medicamentos.php">Medicamentos <span class="sr-only">(current)</span></a></li>
                        <li><a href="diagnosticos.php">Diagnósticos</a></li>
                
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
        if ($usuario["admin"] == 1 ){           //VERIFICA ADMIN
            require_once 'include/DB_Functions.php';
            $db = new DB_Functions();

            $medicamentos = $db->getMedicamentos();
            
        }else{
            echo"Bem-Vindo, convidado <br>";
            echo"Essas informações <font color='red'>NÃO PODEM</font> ser acessadas por você";
            echo"<br><a href='login.html'>Faça Login</a> para ler o conteúdo<br><br><br><br>";
            exit();
        }
     
        ?>

      
        <div class="table-responsive">
        <div class="table" border="2"  >
            <div class="tableHead">
                
                    <div class="cell">#ID</div>
                    <div class="cell">Nome</div>
                    <div class="cell">Descrição</div>
                    <div class="cell">Dose Mínima</div>
                    <div class="cell">Dose Máxima</div>               
                    <div class="cell">Ações</div>
                
            </div>
            
    
                <?php
                    $insereMedicamento = "insereMedicamento";
                 
                    
                    echo "<form class='tableRow' name=$insereMedicamento id=$insereMedicamento action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idMedicamento' id='idMedicamento' value=''/></div>"
                        . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value=''/></div>"
                        . "<div class='cell'><input class='input2' name='descricao' id='descricao' type='text' contentEditable=true value=''/></div>"
                        . "<div class='cell'><input class='input2' name='doseMinima' id='doseMinima' type='number' contentEditable=true value=''/></div>"      
                        . "<div class='cell'><input class='input2' id='doseMaxima' name='doseMaxima' type='number' contentEditable=true value=''></div>"
                        . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='insereMedicamento'/>" 
                        . "<div class='cell'>"
                                  . "<input type='button' class='btn btn-xs btn-success' name='botaoSalva' id='botaoSalva' value='Salvar' onclick='botaoInsereMedicamento_clickHandler($insereMedicamento);'>"          
                                  
                        . "</div> "              
                            
                            ."</form>"
                               
                      . "\n";
                    
                    
                   
                   
                    
                    foreach( $medicamentos as $row ) {          
                        $formName = "form" . $row['idMedicamento'];
                       
                      
                      echo "<form class='tableRow' name=$formName id=$formName action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text' readonly name='idMedicamento' id='idMedicamento' value='{$row['idMedicamento']}'/></div>"
                        . "<div class='cell'><input class='id' name='nome' id='nome' type='text' readonly contentEditable=true value='{$row['nome']}'/></div>"
                        . "<div class='cell'><input class='id' id='descricao' name='descricao' readonly type='text' contentEditable=true value='{$row['descricao']}'></div>"
                        . "<div class='cell'><input class='id' id='doseMinima' name='doseMinima' readonly type='text' contentEditable=true value='{$row['doseMinima']}'></div> " 
                        . "<div class='cell'><input class='id' id='doseMaxima' name='doseMaxima' readonly type='text' contentEditable=true value='{$row['doseMaxima']}'></div>"      
                         . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='apagaMedicamento'/>" .
                          "<div class='cell'>"          
                                  . "<input type='button' class='btn btn-xs btn-danger' value='x' name='botaoApaga' id='botaoApaga' onclick='botaoApagaMedicamento_clickHandler($formName);' >" 
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
          
            
            function botaoApagaMedicamento_clickHandler(form) {
                     //form.elements["tag"].value = "apagaMedicamento";
                     form.submit();
            }
            function botaoInsereMedicamento_clickHandler(form) {
                     //form.elements["tag"].value = "apagaMedicamento";
                     form.submit();
            }

            function toggle_visibility(popUp) {
                if(popUp.style.display === 'block')
                   popUp.style.display = 'none';
                else
                   popUp.style.display = 'block';
             }
             
            function logout(){
               window.alert("aa");
                document.cookie = "login" + '=; Max-Age=0';
               window.location = "index.html"; // TO REFRESH THE PAGE
    
            }
            
        </script>
    </body>
</html>




