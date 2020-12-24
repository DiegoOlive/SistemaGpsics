<?php
require_once "connect.php";
session_start();

$link = new mysqli ('localhost','id13981340_adminstrador','Senha100-senha','id13981340_estoquegpsics');
$link ->set_charset('utf8');    

$conf=0; //usado para mensagem    
$id_user=0; // id de confirmação para as demais paginas

if (isset($_POST['autenticar'])){
    $conf=0; 
    $p_user = filter_input( INPUT_POST, 'p_user', FILTER_SANITIZE_SPECIAL_CHARS);
    $p_senha = filter_input( INPUT_POST, 'p_senha', FILTER_SANITIZE_SPECIAL_CHARS);
    
    //coleta informações na tabela usuários do banco
    $querySelect = $link->query("SELECT *FROM usuarios");
                    
    while($valores = $querySelect->fetch_assoc()):
        $id_user =$valores['id_user'];
        $usuario =$valores['usuario'];
        $senha =$valores['senha'];
    endwhile;

    //verifica a senha e o usuário
    if($p_user==$usuario && $p_senha==$senha){
        $_SESSION['id_user'] = $id_user; 
        header('Location:gpsics_index.php'); 
        
        //$id_user válido autentica as demais paginas
        //caso o navegador senha reiniciado, $id_user retorna a zero
        //necessitando do login novamente
    }else{
        $conf=1; //para mensagem             
    
    }               
}
if (isset($_POST['cancelar'])){
    header('Location:index.php'); 
}
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

    </style>

    </HEAD>

    <BODY BGCOLOR="FFFFFF" TEXT="000000" LINK="0000FF" VLINK="0000FF" Marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>

        <table BORDER=0 height=100% WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>

<!--    ---------------- SEÇÃO ESQUERDA ---------------    -->            
            <td VALIGN=top BGCOLOR=373545 width=13%>
                <CENTER><img src="imagens\leds.jpg"/></A></cENTER>
                <CENTER><img src="imagens\arduino.jpg"/></A></cENTER>
                <CENTER><img src="imagens\varios1.jpg"/></A></cENTER>
                <CENTER><img src="imagens\nano.jpg"/></A></cENTER>
                <CENTER><img src="imagens\varios2.jpg"/></A></cENTER>              
            </td>

<!--    ---------------- SEÇÃO CENTRO ---------------    -->                 
            <td VALIGN=top width=70%>
                <BR><CENTER><img src="imagens\logo.jpg"/></CENTER><BR>               
                <table BORDER=0 WIDTH=85% ALIGN=CENTER BGCOLOR=FE7E01>
                    <tr>                       
                        <td><FONT COLOR=WHITE size=2><BR>                        
                            <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"> 
                                <CENTER><B>Sistema de Gerenciamento de Recursos de Estoque &nbsp;</B>
                            </form>                                                                                              
                        </td>                   
                    </tr>
                </table><BR>
                
                <?php
                    if($conf == 1){               
               ?>                     
               <CENTER>&nbsp;&nbsp;&nbsp;<div class="mensagem">Usuário ou Senha Inválida!</div></CENTER>               
               <?php               
               }?>

<!--    ---------------- SEÇÃO LOGIN ---------------    -->  
            <table WIDTH=100% ALIGN=CENTER>             
                <BR>
                <form action = "<?php echo $_SERVER['PHP_SELF']; ?>"  method="POST"> 
                    <tr><td><CENTER><h2>Usuário: 
                        <input type="text" name="p_user" value="" placeholder="Digite o usuário" required type="text">
                    </h2></CENTER></td><tr>
                    <tr><td><CENTER><h2>&nbsp;&nbsp;Senha: 
                        <input type="password" name="p_senha" value="" placeholder="Digite a senha" required type="text">
                    </h2></CENTER></td><tr>
                    <td>
                    <CENTER>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="submit" name="autenticar" value="Autenticar" style="cursor: pointer">&nbsp;&nbsp;
                        <input type="submit" name="cancelar" value="Cancelar" style="cursor: pointer"></CENTER>
                    <td>               
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