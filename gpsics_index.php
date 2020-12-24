<?php
$date = new DateTime("now", new DateTimezone("America/Fortaleza"));
require_once "connect.php";

//coleta as tags lidas pelo ESP8266-Cliente
if(isset($_GET['tag']) && !empty($_GET['tag'])){
    $tag			= $_GET['tag'];
    $sql 			= "INSERT INTO tags(tag_cadastrada) VALUES('$tag')";
    $resultado		= mysqli_query($connect,$sql) or die(mysqli_error());
    die('Cadastro efetuado');
}

session_start();

$ptag = filter_input(INPUT_GET, "ptag"); //pesquisar por tag
$pnome = filter_input(INPUT_GET, "pnome"); //pesquisar por nome

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
//verifica se o id passado por index é valido
if($id_user==$id_v){
    if ($ptag){
        $sqli    = "SELECT * FROM tags where tag_cadastrada like '$ptag%' ORDER BY id DESC";                
    }
    
    else if ($pnome){
        if($pnome == "Diego"){
            $pnome ='8c3acec4';
            $sqli    = "SELECT * FROM tags where tag_cadastrada like '$pnome%' ORDER BY id DESC";
        }

        else if($pnome == "Veronica"){
            $pnome ='817e491f';
            $sqli    = "SELECT * FROM tags where tag_cadastrada like '$pnome%' ORDER BY id DESC";
        }

        //verificar a tag inserida como visitante, caso desejado, criar novo usuário
        else if($pnome == "Visitante"){  
            $pnome ='XXXXXXXXX'; 
            $sqli    = "SELECT * FROM tags where tag_cadastrada like '$pnome%' ORDER BY id DESC";
        }

        else {
            $sqli    = "SELECT * FROM tags where tag_cadastrada like '$pnome%' ORDER BY id DESC";
        }           
    }
    else if (isset($_POST['pdata'])){ //pesquisar por data
        $pdata =$_POST['pdata'];
        $sqli    = "SELECT * FROM tags where datas like '$pdata%' ORDER BY id DESC";             
    }

    else if (isset($_POST['pult3'])){ //filtrar ultimas 3 tags
        $pnome="";
        $ptag="";
        $sqli    = "SELECT * FROM tags ORDER BY id DESC LIMIT 3";             
    }

    else if (isset($_POST['pult5'])){ //filtrar ultimas 5 tags
        $sqli    = "SELECT * FROM tags ORDER BY id DESC LIMIT 5";
    }

    else if (isset($_POST['pult10'])){ //filtrar ultimas 10 tags
        $sqli    = "SELECT * FROM tags ORDER BY id DESC LIMIT 10";             
    }

    else if (isset($_POST['ptodos'])){  //todas as tags
        $sqli    = "SELECT * FROM tags ORDER BY id DESC";             
    }
    
    else if (isset($_POST['estoque_cadastrar'])){
        header('Location:gpsics_estoque_cadastrar.php');         
    }

    else if (isset($_POST['tags_estoque'])){ //total do estoque e total emprestado
        header('Location:gpsics_estoque.php'); 
    }

    else if (isset($_POST['emprestimo'])){ // realizar emprestimo associando as tag lidas
        header('Location:gpsics_emprestimo.php'); 
    }
    
    else if (isset($_POST['logout'])){ 
        session_destroy();
        header('Location:index.php');
    }
    
    else {
        $sqli  	= "SELECT * FROM tags ORDER BY id DESC";
    }
        
        $result = mysqli_query($connect,$sqli);
        $linha = mysqli_fetch_assoc ($result);
        $total = mysqli_num_rows ($result);
}
else{
    header('Location:index.php');
} // caso não seja autenticado é retornado para a pag de index (autenticação)
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
            <form method="POST">
                <CENTER><img src="imagens\prfid1.jpg"/></A></cENTER>
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
                <BR>
                <form method="POST">
                    <input type="submit" align=”Right” name="logout" value="" class="bt_logout" style="cursor: pointer">
                </form>
                <CENTER>
                    <img src="imagens\logo.jpg"/>
                </CENTER><BR>
                <table BORDER=0 WIDTH=85% ALIGN=CENTER BGCOLOR=FE7E01>
                    <tr>
                       <td><FONT COLOR=WHITE size=2><BR>
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                                <CENTER><B>Tag: &nbsp;</B><input type = "text" name = "ptag"/>
                                <input type = "submit" value = "Buscar"/></CENTER>
                            </form>    
                        </td> 
                        <td><FONT COLOR=WHITE size=2><BR>
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                                <CENTER><B>Usuário: </B><input type = "text" name = "pnome"/>
                                <input type = "submit" value = "Buscar"/></CENTER>
                            </form>    
                        </td>                       
                        <td><FONT COLOR=WHITE size=2><BR>                      
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> 
                                <CENTER><B> Data: </B><input type="date" name ="pdata"/>
                                <input type = "submit" value = "Buscar"/></CENTER>
                            </form>                                          
                        </td>                       
                    </tr>
                </table><BR>

<!--    ---------------- TABELA TAGS_CADASTRADAS ---------------    -->                                  
    <table BORDER=1 WIDTH=80% ALIGN=CENTER>
            <tr>
                <td><h2>ID </h2></td>
                <td><h2>Tags Cadastradas </h2></td>
                <td><h2>Usuários </h2></td>
                <td><CENTER><h2>Hora </h2></CENTER></td>
                <td><CENTER><h2>Data </h2></CENTER></td>
            </tr>      
                 
        <div id="conteudo">
            
            <?php
        	    if($total) { do{
            ?>

            <?php
            $nome;

            if ($linha['tag_cadastrada'] == '8c3acec4'){
                $nome = "Diego";
            }
            else if ($linha['tag_cadastrada'] == '817e491f'){
                $nome = "Veronica";
            }
             else
                $nome = "Visitante"; 
            ?>

               <tr>
                   <td><h2><?php echo $linha['id'];?></h2></td>
                   <td><h2><?php echo $linha['tag_cadastrada'];?></h2></td>
                   <td><h2><?php echo $nome;?></h2></td>
                   <td><CENTER><h2>
                   <?php
                   // AQUI PROFESSORA, TESTES COM A REDUÇÃO -3HORAS
                   //TENTATIVA 1
                   $date = new DateTime($linha['hora']);
                   $date->sub(new DateInterval('PT3H'));
                   
                   //echo $date;
                   if($linha['hora'] < 3){
                      $date = new DateTime($linha['hora']);
                      $date->add(new DateInterval('PT9H'));
                      echo $date->format('h:i:s');   
                   //aqui data
                   
                       
                   }else{
                      echo $date->format('h:i:s');
                   }
                   
                   //lembrando que aqui a verificação vai pelo banco
                   if($linha['hora'] >= 3 && $linha['hora'] <= 15){
                      echo ' A.M.';  
                   }else{
                      echo ' P.M.';
                   }
                   ?>
                   <?php //echo $linha[('hora')]?></h2></CENTER></td>
                   <td><CENTER><h2><?php if($linha['hora'] < 3){echo date('d/m/Y', strtotime('-1 days', strtotime($linha['datas'])));} else echo date ("d/m/Y", strtotime ($linha['datas']));?></h2></CENTER></td>
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