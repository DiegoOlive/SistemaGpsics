<?php
    include_once 'connect.php';
    session_start();
    
    //comunicação com o banco
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
      
    //verifica se o id passado por index (autentica) é valido
    if($id_user==$id_v){
    
        //recebe informacoes de gpsics_estoque_editar
        $id = $_SESSION['id_eq'];  //é passado o id do equipamento  
    
        $queryDelete = $link->query("delete from equipamentos where id_eq='$id'");
    
        if( mysqli_affected_rows($link) > 0):
            header('Location:gpsics_estoque_cadastrar.php');
        endif;
        
    }else // caso não seja autenticado é retornado para a pag de autenticação
    header('Location:index.php');
?>