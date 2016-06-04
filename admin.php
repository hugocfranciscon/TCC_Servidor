<html DOCTYPE>
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
        <title>Admin</title>
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
                        <li class="active"><a href="#">Usuários <span class="sr-only">(current)</span></a></li>
                        <li><a href="medicamentos.php">Medicamentos</a></li>
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
            $usuario = unserialize($_COOKIE["login"]);
            //if ($usuario["admin"] == 1 ){
            if (1) {
                // include db handler
                require_once 'include/DB_Functions.php';
                $db = new DB_Functions();
                $usuarios = $db->getTodosUsuarios();            
            } else {
                echo" Bem-Vindo, convidado <br>";
                echo" Essas informações <font color='red'>NÃO PODEM</font> ser acessadas por você";
                echo"<br><a href='login.html'> Faça Login</a> como admin para ler o conteúdo<br><br><br><br>";
                exit();
            }
        ?>
        <div class="table-responsive">
            <div class="table" border="2">
                <div class="tableHead">                
                    <div class="cell">#ID</div>
                    <div class="cell">Nome</div>
                    <div class="cell">Login</div>
                    <div class="cell">Senha</div>
                    <div class="cell">Tipo</div>
                    <div class="cell" style="width:5px">Admin</div>
                    <div class="cell">Endereço</div>
                    <div class="cell">Telefone</div>
                    <div class="cell">Email</div>            
                    <div class="cell">Ações</div>                
                </div>
                <?php
                    $insereUsuario = "insereUsuario";
                    $insereMedico = "insereMedico";
                    $insereEnfermeira = "insereEnfermeira";
                        
                    echo "<form class='tableRow' name=$insereUsuario id=$insereUsuario action='index.php' method='post'>"
                            . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idUsuario' id='idUsuario' value=''/></div>"
                            . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value=''/></div>"
                            . "<div class='cell'><input class='input2' name='login' id='login' type='text' contentEditable=true value=''/></div>"
                            . "<div class='cell'><input class='input2' name='senha' id='senha' type='password' contentEditable=true value=''/></div>"
                             . "<div class='cell'><select class='input2' name='tipoDeUsuario' id='tipoDeUsuario'>"
                                        ."<option value=1>Médico</option>" 
                                        ."<option value=2>Enfermeira</option>"
                                        ."<option value=3>Recepcionista</option>"
                                ."</select></div>"
                            . "<div class='cell'><input class='id' id='admin' name='admin' style='width:20%' type='checkbox'></div>"
                            . "<div class='cell'><input class='input2' id='endereco' name='endereco' type='text' contentEditable=true value=''></div>"
                            . "<div class='cell'><input class='input2' id='telefone' name='telefone' type='number' contentEditable=true value=''></div>"
                            . "<div class='cell'><input class='input2' id='email' name='email' type='email' contentEditable=true value=''></div>"
                            . "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='insereUsuario'/>" 
                            . "<div class='cell'>"
                                      . "<input type='button' class='btn btn-xs btn-success' name='botaoSalva' id='botaoSalva' value='Salvar' onclick='botaoInsereUsuario_clickHandler($insereUsuario, $insereMedico, $insereEnfermeira);'>"          
                                      
                            . "</div> " 
                            . "<div name=$insereMedico id=$insereMedico class='popupBoxOnePosition'  action='index.php' method='post'>"
                                                 
                                        ."<div class='popupBoxWrapper'>"
                                                ."<div class='popupBoxContent'>" 
                                                        ."<h4>Insere Médico</h4>"
               
                                                          ."<div><label for='especializacao'>Especialização: </label><input type='text' style='width:200px; height:100px;' name='especializacao' id='especializacao' value='' /></div><br><br>" 
                                                  
                                                          ."<div><input type='button' class='btn btn-xs btn-success' name='popupEdita' id='popupEdita' value='Salvar' onclick='botaoInsereMedico_clickHandler($insereUsuario);' >"
                                                        .  "<input type='button' class='btn btn-xs btn-danger' name='popupSair' id='popupSair' value='Cancela' onclick='toggle_visibility($insereMedico);' ></div>"
                                                ."</div>" 
                                        ."</div>"

                           ."</div>"
                          . "<div name=$insereEnfermeira id=$insereEnfermeira class='popupBoxOnePosition'  action='index.php' method='post'>"
                                                 
                                        ."<div class='popupBoxWrapper'>"
                                                ."<div class='popupBoxContent'>" 
                                                        ."<h4>Insere Enfermeira</h4>"
               
                                                          ."<div><label for='ala'>Ala: </label><input type='text' style='width:200px;' name='ala' id='ala' value='' /></div><br><br>" 
                                                  
                                                          ."<div><input type='button' class='btn btn-xs btn-success' name='popupEdita' id='popupEdita' value='Salvar' onclick='botaoInsereEnfermeira_clickHandler($insereUsuario);' >"
                                                        .  "<input type='button' class='btn btn-xs btn-danger' name='popupSair' id='popupSair' value='Cancela' onclick='toggle_visibility($insereEnfermeira);' ></div>"
                                                ."</div>" 
                                        ."</div>"

                           ."</div>"
                                
                                ."</form>"
                                   
                          . "\n";
                        
                    while ($row = mysql_fetch_assoc($usuarios)) {          
                        $formName = "form" . $row['idUsuario'];                      
                        echo "<form class='tableRow' name=$formName id=$formName action='index.php' method='post'>"
                            . "<div class='cell'><input class='id' style='width:100%' type='text'  name='idUsuario' id='idUsuario' value='{$row['idUsuario']}'/></div>"
                            . "<div class='cell'><input class='input2' name='nome' id='nome' type='text' contentEditable=true value='{$row['nome']}'/></div>"
                            . "<div class='cell'><input class='input2' id='login' name='login' type='text' contentEditable=true value='{$row['login']}'></div>"
                            . "<div class='cell'><input class='input2' id='senha' name='senha' type='password' contentEditable=true placeholder='*****'value=''></div> " 
                                . "<div class='cell'><select class='input2' name='tipoDeUsuario' id='tipoDeUsuario' >"
                                        ."<option value=1 "; echo ($row['tipoDeUsuario']==1 ? 'selected' :''); echo ">Médico</option>" 
                                        ."<option value=2 "; echo ($row['tipoDeUsuario']==2 ? 'selected' :''); echo ">Enfermeira</option>"
                                        ."<option value=3 "; echo ($row['tipoDeUsuario']==3 ? 'selected' :''); echo ">Recepcionista</option>"
                                ."</select></div>"
                            . "<div class='cell'><input class='id' id='admin' name='admin' type='checkbox' style='width:20%' "; echo ($row['admin']==1 ? 'checked' :''); echo "></div>"                    
                            . "<div class='cell'><input class='input2' id='endereco' name='endereco' type='text' contentEditable=true value='{$row['endereco']}'></div>"
                            . "<div class='cell'><input class='input2' id='telefone' name='telefone' type='number' contentEditable=true value='{$row['telefone']}'></div>"
                            . "<div class='cell'><input class='input2' id='email' name='email' type='text' contentEditable=true value='{$row['email']}'></div>".
                              "<input class='cell' style = 'display:none' type='text' name='tag' id='tag' value='tag'/>" .
                              "<div class='cell'>"
                                      . "<input type='button' class='btn btn-xs btn-success' name='botaoEdita' id='botaoEdita' value='Salvar' onclick='botaoEditaUsuario_clickHandler($formName);' >"          
                                      . "<input type='button' class='btn btn-xs btn-danger' value='x' name='botaoApaga' id='botaoApaga' onclick='botaoApagaUsuario_clickHandler($formName);' >" 
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
            function botaoEditaUsuario_clickHandler(form) {
                form.elements["tag"].value = "editaUsuario";
                form.submit();
            }
            
            function botaoApagaUsuario_clickHandler(form) {
                form.elements["tag"].value = "apagaUsuario";
                form.submit();
            }            
            
            function botaoInsereUsuario_clickHandler(form, formMedico, formEnfermeira) {
                form.elements["tag"].value = "insereUsuario";
                if (form.elements["tipoDeUsuario"].value === '1') {
                    formMedico.style.display = 'block'
                }else if(form.elements["tipoDeUsuario"].value === '2') {
                    formEnfermeira.style.display = 'block'
                } else {
                    form.submit();
                }
            }
            
            function botaoInsereMedico_clickHandler(form) {
                form.style.display = 'none';
                form.submit();
            }
            
            function botaoInsereEnfermeira_clickHandler(form) {
                form.style.display = 'none';
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
