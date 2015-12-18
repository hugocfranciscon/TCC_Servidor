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
        <title>Diagnósticos-Admin</title>
        

        
       
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
                        <li  ><a  href="medicamentos.php">Medicamentos <span class="sr-only">(current)</span></a></li>
                        <li class="active"><a href="diagnosticos.php">Diagnósticos <span class="sr-only">(current)</span></a></li>
                
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
            $usuario = unserialize($_COOKIE["login"]);
            if ($usuario["admin"] == 1 ){
                require_once 'include/DB_Functions.php';
                $db = new DB_Functions();

                $diagnosticos = $db->getDiagnosticos();
                
            }else{
            echo" Bem-Vindo, convidado <br>";
            echo" Essas informações <font color='red'>NÃO PODEM</font> ser acessadas por você";
            echo"<br><a href='login.html'> Faça Login</a> como admin para ler o conteúdo<br><br><br><br>";
            exit();
        }
     
        ?>

      
        <div class="table-responsive">
        <div class="table" border="2"  >
            <div class="tableHead">
                
                    <div class="cell">#ID</div>
                    <div class="cell">Nome</div>
                    <div class="cell">Temperatura mín.</div>
                    <div class="cell">Temperatura máx.</div>
                    <div class="cell">Batimentos mín.</div>               
                    <div class="cell">Batimentos máx.</div>
                    <div class="cell">Glicose mín.</div>
                    <div class="cell">Glicose máx.</div>
                    <div class="cell">Saturação O2 mín</div>
                    <div class="cell">Saturação O2  máx.</div>
                    <div class="cell">Pressão Sis. mín.</div>
                    <div class="cell">Pressão Sis. máx.</div>
                    <div class="cell">Pressão Dia. mín.</div>
                    <div class="cell">Pressão Dia. máx.</div>
                    <div class="cell">Função Pulm. mín.</div>
                    <div class="cell">Função Pulm. máx.</div>
                    <div class="cell">Ações</div>
                
            </div>
            
    
                <?php
                    $insereDiagnostico = "insereDiagnostico";
                 
                    
                    echo "<form class='tableRow' name=$insereDiagnostico id=$insereDiagnostico action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idDiagnóstico' id='idDiagnóstico' value=''/></div>"
                        . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value=''/></div>"
                        . "<div class='cell'><input class='input2' name='minTemperatura' id='minTemperatura' type='number' contentEditable=true value='NULL'/></div>"
                        . "<div class='cell'><input class='input2' name='maxTemperatura' id='maxTemperatura' type='number' contentEditable=true value=''/></div>"      
                        . "<div class='cell'><input class='input2' id='minGlicose' name='minGlicose' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='maxGlicose' name='maxGlicose' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='minSistolica' name='minSistolica' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='maxSistolica' name='maxSistolica' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='minDiastolica' name='minDiastolica' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='maxDiastolica' name='maxDiastolica' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='minTaxaBatimentos' name='minTaxaBatimentos' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='maxTaxaBatimentos' name='maxTaxaBatimentos' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='minSaturacao' name='minSaturacao' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='maxSaturacao' name='maxSaturacao' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='minFuncaoPulmonar' name='minFuncaoPulmonar' type='number' contentEditable=true value=''></div>"
                        . "<div class='cell'><input class='input2' id='maxFuncaoPulmonar' name='maxFuncaoPulmonar' type='number' contentEditable=true value=''></div>"
                        . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='insereDiagnostico'/>" 
                        . "<div class='cell'>"
                                  . "<input type='button' class='btn btn-xs btn-success' name='botaoSalva' id='botaoSalva' value='Salvar' onclick='botaoInsereDiagnostico_clickHandler($insereDiagnostico);'>"          
                                  
                        . "</div> "              
                            
                            ."</form>"
                               
                      . "\n";
                    
                    
                   
                   
                    
                    foreach( $diagnosticos as $row ) {          
                        $formName = "form" . $row['idDiagnostico'];
                       
                      
                      echo "<form class='tableRow' name=$formName id=$formName action='index.php' method='post'>"
                        . "<div class='cell'><input class='id' style='width:30px;' readonly type='text'  name='idDiagnostico' id='idDiagnostico' value='{$row['idCID']}'/></div>"
                        . "<div class='cell'><input class='id'  style='width:110px' readonly name='nome' id='nome' type='text' contentEditable=true value='{$row['nome']}'/></div>"
                        . "<div class='cell'><input class='id' name='minTemperatura' readonly id='minTemperatura' type='number' contentEditable=true value='{$row['minTemperatura']}'/></div>"
                        . "<div class='cell'><input class='id' name='maxTemperatura' readonly id='maxTemperatura' type='number' contentEditable=true value='{$row['maxTemperatura']}'/></div>"      
                        . "<div class='cell'><input class='id' id='minGlicose' readonly name='minGlicose' type='number' contentEditable=true value='{$row['minGlicose']}'></div>"
                        . "<div class='cell'><input class='id' id='maxGlicose' readonly name='maxGlicose' type='number' contentEditable=true value='{$row['maxGlicose']}'></div>"
                        . "<div class='cell'><input class='id' id='minSistolica' readonly name='minSistolica' type='number' contentEditable=true value='{$row['minSistolica']}'></div>"
                        . "<div class='cell'><input class='id' id='maxSistolica' readonly name='maxSistolica' type='number' contentEditable=true value='{$row['maxSistolica']}'></div>"
                        . "<div class='cell'><input class='id' id='minDiastolica' readonly name='minDiastolica' type='number' contentEditable=true value='{$row['minDiastolica']}'></div>"
                        . "<div class='cell'><input class='id' id='maxDiastolica'readonly  name='maxDiastolica' type='number' contentEditable=true value='{$row['maxDiastolica']}'></div>"
                        . "<div class='cell'><input class='id' id='minTaxaBatimentos' readonly name='minTaxaBatimentos' type='number' contentEditable=true value='{$row['minTaxaBatimentos']}'></div>"
                        . "<div class='cell'><input class='id' id='maxTaxaBatimentos' readonly name='maxTaxaBatimentos' type='number' contentEditable=true value='{$row['maxTaxaBatimentos']}'></div>"
                        . "<div class='cell'><input class='id' id='minSaturacao' readonly name='minSaturacao' type='number' contentEditable=true value='{$row['minSaturacao']}'></div>"
                        . "<div class='cell'><input class='id' id='maxSaturacao' readonly name='maxSaturacao' type='number' contentEditable=true value='{$row['maxSaturacao']}'></div>"
                        . "<div class='cell'><input class='id' id='minFuncaoPulmonar' readonly name='minFuncaoPulmonar' type='number' contentEditable=true value='{$row['minFuncaoPulmonar']}'></div>"
                        . "<div class='cell'><input class='id' id='maxFuncaoPulmonar' readonly name='maxFuncaoPulmonar' type='number' contentEditable=true value='{$row['maxFuncaoPulmonar']}'></div>"      
                         . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='apagaDiagnostico'/>" .
                          "<div class='cell'>"          
                                  . "<input type='button' class='btn btn-xs btn-danger' value='x' name='botaoApaga' id='botaoApaga' onclick='botaoApagaDiagnostico_clickHandler($formName);' >" 
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
          
            
            function botaoApagaDiagnostico_clickHandler(form) {
                     //form.elements["tag"].value = "apagaMedicamento";
                     form.submit();
            }
            function botaoInsereDiagnostico_clickHandler(form) {
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
                document.cookie = "login" + '=; Max-Age=0';
               window.location = "index.html"; // TO REFRESH THE PAGE
    
            }
            
            
        </script>
    </body>
</html>




