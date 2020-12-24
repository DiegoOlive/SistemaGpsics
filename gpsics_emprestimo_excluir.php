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
        $id_at = $_SESSION['id_at'];  //é passado o id do equipamento  
    
        $queryDelete = $link->query("delete from assoc_tag where id_at='$id_at'");
    
        if( mysqli_affected_rows($link) > 0):
            header('Location:gpsics_emprestimo.php');
        endif;
        
    }else // caso não seja autenticado é retornado para a pag de autenticação
    header('Location:index.php');
?>