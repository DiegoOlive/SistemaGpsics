<?php
session_start();
require_once "connect.php";

    $p_tag = filter_input(INPUT_GET, "p_tag"); //busca por tag
    $p_nome = filter_input(INPUT_GET, "p_nome");//busca por nome
    $p_equip = filter_input(INPUT_GET, "p_equip");//busca por equipamento       
           
    $c_idtag = filter_input( INPUT_POST, 'c_idtag', FILTER_SANITIZE_NUMBER_INT);//cadastrar id de tag lida ESP8266-Cliente       
    $c_quantidade = filter_input( INPUT_POST, 'c_quantidade', FILTER_SANITIZE_NUMBER_INT);//cadastrar quantidade (equipamento)
    $pesq=0; //verificação para mensagem se já é um equipamento cadastrado
   
    $link = new mysqli ('localhost','id13981340_adminstrador','Senha100-senha','id13981340_estoquegpsics');
    $link ->set_charset('utf8');
    
    //verificacao de login
    if(isset($_SESSION['id_user'])){
        $id_user = $_SESSION['id_user'];
    }else{
        header('Location:index.php');
    }  
    
    $querySelect = $link->query("SELECT *FROM usuarios");
                     
    while($v = $querySelect->fetch_assoc()):
        $id_v =$v['id_user'];
    endwhile;
    
    //verifica se o id passado por gpsics_autentica é valido
    if($id_user==$id_v){

       if($p_tag){
            $sqli    = "SELECT * FROM assoc_tag where tag_at like '$p_tag%' ORDER BY id_at DESC"; 
        }

        else if ($p_nome){
            if($p_nome == "Diego"){
                $p_nome ='8c3acec4';
                $sqli    = "SELECT * FROM assoc_tag where tag_at like '$p_nome%' ORDER BY id_at DESC";
            }

            else if($p_nome == "Veronica"){
                $p_nome ='817e491f';
                $sqli    = "SELECT * FROM assoc_tag where tag_at like '$p_nome%' ORDER BY id_at DESC";
            }

            //busca por nome, necessária inserção aqui
            else if($p_nome == "Visitante"){ 
                $p_nome ='XXXXXXXXX'; 
                $sqli    = "SELECT * FROM assoc_tag where tag_at like '$p_nome%' ORDER BY id_at DESC";
            }

            else {
                $sqli    = "SELECT * FROM assoc_tag where tag_at like '$p_nome%' ORDER BY id_at DESC";
            }           
        }

        else if ($p_equip){          
            $sqli    = "SELECT * FROM assoc_tag where equip_at like '$p_equip%' ORDER BY id_at DESC";                          
        }

        else if (isset($_POST['p_data'])){ // busca por data
            $p_data =$_POST['p_data'];
            $sqli    = "SELECT * FROM assoc_tag where data_at like '$p_data%' ORDER BY id_at DESC";             
        }       
        
        else if (isset($_POST['pult3'])){ //filtrar ultimas 3 tags
            header('Location:gpsics_index.php');
        }

        else if (isset($_POST['pult5'])){ //filtrar ultimas 5 tags
            header('Location:gpsics_index.php');
        }

        else if (isset($_POST['pult10'])){ //filtrar ultimas 10 tags
            header('Location:gpsics_index.php');
        }

        else if (isset($_POST['ptodos'])){ //todas as tags
            header('Location:gpsics_index.php');
        }
        
        else if (isset($_POST['estoque_cadastrar'])){
            header('Location:gpsics_estoque_cadastrar.php');
        }

        else if (isset($_POST['tags_estoque'])){ //total do estoque e total emprestado
            header('Location:gpsics_estoque.php'); 
        }

        else if (isset($_POST['emprestimo'])){
            header('Location:gpsics_emprestimo.php');            
        }
        
        else if (isset($_POST['devolver'])){
            $_SESSION['id_at'] = $id_at;   
            header('Location:gpsics_emprestimo_excluir.php');            
        }
            
        else if (isset($_POST['logout'])){ 
            session_destroy();
            header('Location:index.php');
        }

        else if (isset($_POST['salvar'])){
            $c_equipamento =$_POST['c_equipamento'];
                //Coleta de informações das tags cadastradas ESP266-Cliente            
            $querySelect = $link->query("select *from tags where id = '$c_idtag'");
            $v_id=0;
                while($valores = $querySelect->fetch_assoc()):
                    $v_id =$valores['id'];
                    $a_tag_at =$valores['tag_cadastrada'];
                    $a_data_at =$valores['datas'];
                    $a_hora_at =$valores['hora'];
                endwhile;

                //Coleta da quantidade de equipamentos
                $querySelect = $link->query("select *from equipamentos where equipamento = '$c_equipamento'");
        
                while($valor = $querySelect->fetch_assoc()):
                    $a_equip_at =$valor['quantidade'];
                endwhile;
                //verificação se é uma tag válida           
                if($c_idtag==$v_id){
                    //verificação da quantidade digitada maior que a quantidade em estoque
                    if($c_quantidade <= $a_equip_at){
                            $quant_ut=0;
                            $quant_par=0;
                        $querySelect = $link->query("select *from assoc_tag where equip_at = '$c_equipamento'");
                    while($varif = $querySelect->fetch_assoc()):
                        $quant_u = $varif['quant_at'];
                        $quant_ut = $quant_ut + $quant_u;   
                    endwhile;
                    $quant_par = $quant_ut + $c_quantidade;
                    
                    //verificação da quantidade digitada + quantidades emprestadas
                    //é maior que a quantidade em estoque (quantidade parcial)
                        if($quant_par<= $a_equip_at){
                        //cadastro do emprestimo    
                            $queryInsert =$link ->query ("insert into assoc_tag values(default,'$v_id','$a_tag_at','$a_data_at','$a_hora_at','$c_equipamento','$quant_par','$a_equip_at','$c_quantidade')");                                   
                        }else{
                            $pesq=1; //para mensagem 
                        }
                    }else{
                        $pesq=1; //para mensagem    
                    }
                    
                }else{   
                     $pesq=2; //para mensagem                         
                }                    
                
                $sqli    = "SELECT * FROM assoc_tag ORDER BY id_at DESC";
            
        }else {
           $sqli    = "SELECT * FROM assoc_tag ORDER BY id_at DESC";        
        }
            
            $result = mysqli_query($connect,$sqli);
            $linha = mysqli_fetch_assoc ($result);
            $total = mysqli_num_rows ($result);
    } else // caso não seja autenticado é retornado para a pag de autenticação
    header('Location:index.php');        
    ?>
    
<HTML>
    <HEAD>
    <meta charset="utf-8"/>
    <title>Banco de dados GPSISCS</title>   

    <style type="text/css">
	    body,td			{ font-family: arial, helvetica; color: #000000; font-size: 12px; }
	 	A:link			{ text-decoration: none; color: #2244ee; }
	    A:visited		{ text-decoration: none; color: #2244ee; }
	    A:hover			{ text-decoration: underline; }

	    .hidden			{ font-size: 8px; font-weight: bold; color: #ffffff; }
	    A:link.hidden		{ text-decoration: none; color: #ffffff; }
	    A:visited.hidden	{ text-decoration: none; color: #ffffff; }
	    A:hover.hidden		{ text-decoration: none; color: #ffffff; }

	    .small			{ font-size: 10px; }
	    .emph			{ font-weight: bold; }


        .bt_pult3 {
            border: 0px;
            background-image: url(imagens/pult3.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_pult3:hover {
            background-image: url(imagens/pult3.jpg);
        }

        .bt_pult5 {
            border: 0px;
            background-image: url(imagens/pult5.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_pult5:hover {
            background-image: url(imagens/pult5.jpg);
        }

        .bt_pult10 {
            border: 0px;
            background-image: url(imagens/pult10.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_pult10:hover {
            background-image: url(imagens/pult10.jpg);
        }

        .bt_ptodos {
            border: 0px;
            background-image: url(imagens/ptodos.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_ptodos:hover {
            background-image: url(imagens/ptodos.jpg);
        }

        .bt_estoque_cadastrar {
            border: 0px;
            background-image: url(imagens/estoque_cadastrar.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_estoque_cadastrar:hover {
            background-image: url(imagens/estoque_cadastrar.jpg);
        }

        .bt_tags_estoque {
            border: 0px;
            background-image: url(imagens/tags_estoque.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_tags_estoque:hover {
            background-image: url(imagens/tags_estoque.jpg);
        }

        .bt_emprestimo {
            border: 0px;
            background-image: url(imagens/emprestimo.jpg);
            background-repeat: no-repeat;
            width: 203px; 
            height: 43px;
        }
        
        .bt_emprestimo:hover {
            background-image: url(imagens/emprestimo.jpg);
        }

        .bt_salvar {
            border: 0px;
            background-image: url(imagens/salvar.jpg);
            background-repeat: no-repeat;
            width: 50px; 
            height: 50px;
        }
        
        .bt_salvar:hover {
            background-image: url(imagens/salvar.jpg);
        }
        
        .bt_devolver {
            border: 0px;
            background-image: url(imagens/devolver.jpg);
            background-repeat: no-repeat;
            width: 50px; 
            height: 50px;
        }
        
        .bt_devolver:hover {
            background-image: url(imagens/devolver.jpg);
        }
        
        .bt_logout {
            border: 0px;
            background-image: url(imagens/logout.jpg);
            background-repeat: no-repeat;
            width: 100px; 
            height: 50px;
            float:right;
        }
        
        .bt_logout:hover {
            background-image: url(imagens/logout.jpg);
        }
        
    </style>
    </HEAD>

    <BODY BGCOLOR="FFFFFF" TEXT="000000" LINK="0000FF" VLINK="0000FF" Marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>

        <table BORDER=0 height=100% WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
           
    <!--    ---------------- SEÇÃO ESQUERDA ---------------    -->
            <td VALIGN=top BGCOLOR=373545 width=13%>
                <CENTER><img src="imagens\prfid1.jpg"/></A></CENTER>
                <form method="POST">
                    <CENTER><input type="submit" name="pult3" value= "" class="bt_pult3" style="cursor: pointer"/></CENTER>
                    <BR>
                    <CENTER><input type="submit" name="pult5" value= "" class="bt_pult5" style="cursor: pointer"/></CENTER>
                    <BR>
                    <CENTER><input type="submit" name="pult10" value= "" class="bt_pult10" style="cursor: pointer"/></CENTER>
                    <BR>
                    <CENTER><input type="submit" name="ptodos" value= "" class="bt_ptodos" style="cursor: pointer"/></CENTER>
                    <BR>     
                    <CENTER><img src="imagens\estoque.jpg"/></A></cENTER>                            
                    <BR>
                    <CENTER><input type="submit" name="estoque_cadastrar" value= "" class="bt_estoque_cadastrar" style="cursor: pointer"/></CENTER>
                    <BR>
                    <CENTER><input type="submit" name="tags_estoque" value= "" class="bt_tags_estoque" style="cursor: pointer"/></CENTER>
                    <BR>
                    <CENTER><input type="submit" name="emprestimo" value= "" class="bt_emprestimo" style="cursor: pointer"/></CENTER>
                </form>
            </td>

    <!--    ---------------- SEÇÃO CENTRO ---------------    -->
    <!--    ---------------- SEÇÃO BUSCA ---------------    -->
            <td VALIGN=top width=70%>
                <BR><form method="POST">
                <input type="submit" align=”Right” name="logout" value="" class="bt_logout" style="cursor: pointer">
            </form>
            <CENTER><img src="imagens\logo.jpg"/></CENTER><BR>
                <table BORDER=0 WIDTH=100% ALIGN=CENTER BGCOLOR=FE7E01>
                    <tr>
                        <td><FONT COLOR=WHITE size=2><BR>
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                               <CENTER><B>Tag: &nbsp;</B><input type = "text" name = "p_tag"/>
                               <input type = "submit" value = "Buscar"/></CENTER>
                            </form>  
                        </td> 
                        <td><FONT COLOR=WHITE size=2><BR>
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                               <CENTER><B>Usuário: </B><input type = "text" name = "p_nome"/>
                               <input type = "submit" value = "Buscar"/></CENTER>
                            </form>    
                        </td>
                        <td><FONT COLOR=WHITE size=2><BR>                        
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                                <CENTER><B>Equip: &nbsp;</B><input type = "text" name = "p_equip"/>
                                <input type = "submit" value = "Buscar"/></CENTER>
                            </form>                                                                                              
                        </td>                        
                        <td><FONT COLOR=WHITE size=2><BR>                      
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> 
                                <CENTER><B>Data: </B><input type="date" name ="p_data"/>
                                <input type = "submit" value = "Buscar"/></CENTER>
                            </form>                                          
                        </td>                                                              
                                           
                    </tr>
                </table><BR>

    <!--    ---------------- SEÇÃO ALTERAR EMPRÉSTIMO---------------    -->
            <CENTER><h2>Devolver Empréstimo de Equipamento</h2></CENTER>
            <table BORDER=0 WIDTH=50% ALIGN=CENTER>           
            
            <?php //informações passadas para gpsics_emprestimo_excluir            
                $id_at = filter_input(INPUT_GET, 'id_at', FILTER_SANITIZE_NUMBER_INT);
                $_SESSION['id_at'] = $id_at;               
                $querySelect = $link->query("select *from assoc_tag where id_at='$id_at'");
            
                while($registro = $querySelect->fetch_assoc()):
                    $id_ant_tag = $registro['id_ant_tag'];
                    $equip_at = $registro['equip_at'];
                    $quant_at = $registro['quant_at'];
                    $tag_at = $registro['tag_at'];
                    $data_at = $registro['data_at'];
                    $hora_at = $registro['hora_at'];
                endwhile;
                
                
            ?> 
            
             <!--    ---------------- SEÇÃO CADASTRAR EMPRESTIMO ---------------    -->           
             
                    <td><h2>ID de Empréstimo: 
                        <input  name="" value="<?php echo $id_at ?>">
                    </h2></td>
                    <td><h2>Tag do Usuário:
                        <input  name="" value="<?php echo $tag_at ?>">  
                    </h2></td>
                    <td><h2>Data: 
                        <input  name="" value="<?php if($hora_at < 3){echo date('d/m/Y', strtotime('-1 days', strtotime($data_at)));} else echo date ("d/m/Y", strtotime ($data_at));?> ">
                    </h2></td>
                    <td><h2>Hora:
                    <?php
                    
                   // AQUI PROFESSORA, TESTES COM A REDUÇÃO -3HORAS
                   $_SESSION['id_at'] = $id_at;               
                   $querySelect = $link->query("select *from assoc_tag where id_at='$id_at'");
                
                while($registro = $querySelect->fetch_assoc()):
                    $hora_at = $registro['hora_at'];
                endwhile;
                   
                   //TENTATIVA 1
                   $date = new DateTime($hora_at);
                   $date->sub(new DateInterval('PT3H'));
                   
                   //echo $date;
                   if($hora_at < 3){
                      $date = new DateTime($hora_at);
                      $date->add(new DateInterval('PT9H'));
                      $date->format('h:i:s');   
                   }else{
                      $date->format('h:i:s');
                   }
                   
                   //lembrando que aqui a verificação vai pelo banco
                   if($hora_at >= 3 && $hora_at <= 15){
                     //echo ' A.M.';
                      $m =' A.M.';
                   }else{
                   //echo ' P.M.';
                      $m= ' P.M.';
                   }
                   ?>
                   
                   
                   <?php //echo $date->format('h:i:s') ?> <?php// echo $m ?>
                        <input  name="" value="<?php echo $date->format('h:i:s') ?> <?php echo $m ?>">
                    <?php //echo $hora_at ?>
                    </h2></td>
                    <td><h2>Equipamento:
                        <input  name="" value="<?php echo $equip_at ?>">  
                    </h2></td>
                    <td><h2>Quantidade: 
                        <input  name="" value="<?php echo $quant_at ?>">
                <form action = "gpsics_emprestimo_excluir.php" method="POST">
                    <td>    
                    <input type="submit" name="devolver" value=""  title="Devolver" class="bt_devolver" style="cursor: pointer">
                    <td>                                          
                </form>
            <tr>                
        </table>
        <BR>

    <!--    ---------------- SEÇÃO TABELA EMPRESTIMOS ---------------    -->
            <table BORDER=1 WIDTH=95% ALIGN=CENTER>           
                <tr>
                    <td><h2>ID </h2></td>
                    <td><h2>Tag Cadastrada </h2></td>
                    <td><h2>Nome </h2></td>
                    <td><CENTER><h2>Data </h2></CENTER></td>
                    <td><CENTER><h2>Hora </h2></CENTER></td>
                    <td><CENTER><h2>Equipamento </h2></CENTER></td>
                    <td><CENTER><h2>Quant. Emprestada </h2></CENTER></td>
                    <td><CENTER><h2>Ação </h2></CENTER></td>
                </tr>      
                 
            <div id="conteudo">
            <?php
        	    if($total) { do{
            ?>               

            <?php
            if ($linha['tag_at'] == '8c3acec4'){
                $nome = "Diego";
            }
            else if ($linha['tag_at'] == '817e491f'){
                $nome = "Veronica";
            }
             else
                $nome = "Visitante";
            ?>
                
                <tr>                    
                    <td><h2><?php echo $linha['id_at'];?></h2></td>
                    <td><h2><?php echo $linha['tag_at'];?></h2></td>
                    <td><h2><?php echo $nome;?></h2></td>
                    
                    <td><CENTER><h2><?php if($linha['hora_at'] < 3){echo date('d/m/Y', strtotime('-1 days', strtotime($linha['data_at'])));} else echo date ("d/m/Y", strtotime ($linha['data_at']));?></h2></CENTER></td>
                    <td><CENTER><h2>
                    <?php
                   // AQUI PROFESSORA, TESTES COM A REDUÇÃO -3HORAS
                   //TENTATIVA 1
                   $date = new DateTime($linha['hora_at']);
                   $date->sub(new DateInterval('PT3H'));
                   
                   //echo $date;
                   if($linha['hora_at'] < 3){
                      $date = new DateTime($linha['hora_at']);
                      $date->add(new DateInterval('PT9H'));
                      echo $date->format('h:i:s');   
                   }else{
                      echo $date->format('h:i:s');
                   }
                   
                   //lembrando que aqui a verificação vai pelo banco
                   if($linha['hora_at'] >= 3 && $linha['hora_at'] <= 15){
                     echo ' A.M.';  
                   }else{
                   echo ' P.M.';
                   }
                   ?>   
                    
                    <?php //echo $linha['hora_at'];?></h2></CENTER></td>
                    <td><CENTER><h2><?php echo $linha['equip_at'];?></h2></CENTER></td>
                    <td><CENTER><h2><?php echo $linha['quant_at'];?></h2></CENTER></td>
                    <td><CENTER>
                    <?php $id=$linha['id_at'];                      						 
                        echo "<a name='id_at' href='gpsics_emprestimo.php?id=$id_at'><i class='material-icone'>Retornar</i>"
                    ?> 
                    </CENTER></td>  
                </tr>
             <?php
                }while ($linha = mysqli_fetch_assoc($result));
                    mysqli_free_result($result);}          
            ?>                       
            </div>           
            </table><BR>
<!--    ---------------- SEÇÃO INFERIOR ---------------    -->

        <table BORDER=0 WIDTH=85% ALIGN=CENTER BGCOLOR=FE7E01>
            <TR><TD></TD></TR>
        </table><BR>
        <CENTER><FONT SIZE=2>
        2020 <i>Grupo de Pesquisa em Sistemas Cr&iacute;ticos de Seguran&ccedil;a (GPSiCS) </i></a>.
        </FONT></cENTER><BR>   
    </BODY>
</HTML>