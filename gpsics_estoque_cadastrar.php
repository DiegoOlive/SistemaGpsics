<?php
require_once "connect.php";
    session_start();

       $equip = filter_input(INPUT_GET, "equip"); //busca por equipamento          
       $nome = filter_input( INPUT_POST, 'nome_e', FILTER_SANITIZE_SPECIAL_CHARS); //nome do equipamento p/ cadastro
       $quantidade = filter_input( INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT); //quantidade do equipamento p/ cadastro
       $pesq= 0; //verificação se o equipamento já é cadastrado
   
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
    
        if ($equip){          
            $sqli    = "SELECT * FROM equipamentos where equipamento like '$equip%' ORDER BY id_eq DESC";
        }   
        
               
        else if (isset($_POST['pult3'])){ //filtrar ultimas 3 tags
            header('Location:gpsics_index.php');
        }

        else if (isset($_POST['pult5'])){  //filtrar ultimas 5 tags
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
        
        else if (isset($_POST['logout'])){ 
            session_destroy();
            header('Location:index.php');
        }

        else if (isset($_POST['salvar'])){
            $pesq= 1; // apenas para mensagem
            //nome do equipamento é configurado no banco como unico
            $queryInsert =$link ->query ("insert into equipamentos values(default,'$nome','$quantidade')");                                   
            $sqli    = "SELECT * FROM equipamentos ORDER BY id_eq DESC";
                     
        }

        else {
          $sqli    = "SELECT * FROM equipamentos ORDER BY id_eq DESC";
        }
        
        $result = mysqli_query($connect,$sqli);
        $linha = mysqli_fetch_assoc ($result);
        $total = mysqli_num_rows ($result);
    }
    else // caso não seja autenticado é retornado para a pag de autenticação
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
                <CENTER><img src="imagens\prfid1.jpg"/></A></cENTER>                         
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
                <table BORDER=0 WIDTH=85% ALIGN=CENTER BGCOLOR=FE7E01>
                    <tr>
                       <td><FONT COLOR=WHITE size=2><BR>
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                            <CENTER><B>Equipamento: &nbsp;</B><input type = "text" name = "equip"/>
                             <input type = "submit" value = "Buscar"/></CENTER>
                             </form>
                        </td>                                               
                    </tr>
                </table><BR>

    <!--    ---------------- SEÇÃO CADASTRAR EQUIPAMENTO ---------------    -->
        <CENTER><h2>Cadastrar Equipamento</h2></CENTER>

        <table BORDER=0 WIDTH=48% ALIGN=CENTER>
            <tr>
            
            <?php
                if($pesq == 1){
                if ($nome != $linha['equipamento'] && $nome != "") {
            ?>
                <CENTER><div class="sucesso">Equipamento já Cadastrado!</div></CENTER>
            <?php
            }}?>
            
            <?php
            //informações passadas para gpsics_estoque_update 
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $_SESSION['id_eq'] = $id;               
            $querySelect = $link->query("select *from equipamentos where id_eq='$id'");
            
            while($registro = $querySelect->fetch_assoc()):
                $equipamento =$registro['equipamento'];
                $quantidade =$registro['quantidade'];
            endwhile;
            ?>
    
            <!--coleta para cadastro do nome do equipamento e quantidade-->
                <form action = "<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> 
                    <td><h2>Nome: 
                    <input type="text" name="nome_e" value="" required type="text">
                    </h2></td>
                    <td><h2>Quantidade:
                    <input name="quantidade" value="" required type="int">
                    </h2></td>
                    <td>
                    <input type="submit" name="salvar" value="" class="bt_salvar" title="Salvar"  style="cursor: pointer">
                    <td>
                </form>
            <tr>                
            </table> 

            <BR>
            <!--    ---------------- SEÇÃO TABELA EQUIPAMENTO ---------------    -->
            <table BORDER=1 WIDTH=80% ALIGN=CENTER>          
            <tr>
                <td><h2>ID </h2></td>
                <td><h2>Equipamento </h2></td>
                <td><CENTER><h2>Quantidade </h2></CENTER></td>
                <td><CENTER><h2>Ação </h2></CENTER></td>
            </tr>      
                 
            <div id="conteudo">
            
            <?php
        	    if($total) { do{
            ?>
                <tr>                    
                   <td><h2><?php echo $linha['id_eq'];?></h2></td>
                   <td><h2><?php echo $linha['equipamento'];?></h2></td>
                   <td><CENTER><h2><?php echo $linha['quantidade'];?></h2></CENTER></td>
                   <td><CENTER>
                   <?php $id=$linha['id_eq'];
                   
                       echo "<a name='id_e' href='gpsics_estoque_editar.php?id=$id'><i class='material-icone'>Editar</i>"
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