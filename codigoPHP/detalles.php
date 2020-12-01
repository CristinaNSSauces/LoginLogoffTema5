<?php
    /**
        *@author: Cristina Núñez
        *@since: 26/11/2020
    */ 
    session_start();//Reanudamos la sesión existente
    
    if(isset($_REQUEST['volver'])){//Si el usuario pulsa el botón de salir
        header('Location: programa.php');//Redirigimos al usuario al index del tema 5
        exit;
    }
    
    if (!isset($_SESSION['usuarioDAW215LoginLogoffTema5'])) {//Si el usuario no se ha autentificado
        header('Location: login.php');//Redirigimos al usuario al ejercicio01.php para que se autentifique
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles</title>         
    </head>
    <body>
        <form  name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <button type="submit" name='volver' value="volver" class="volver">VOLVER</button>
        </form>
        <table style="margin:auto; ">
            
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_COOKIE</td>
        </tr>
            <?php
            foreach ($_COOKIE as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
        <tr>
           
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_SESSION</td>
        </tr>
            <?php
            if(isset($_SESSION)){
                foreach ($_SESSION as $key => $value) {
                ?>
                    <tr>
                        <td style="background-color:#7be4cf;"><?php echo $key?></td>
                        <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                    </tr>
                <?php
                }
            }
            
            ?>
            
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_SERVER</td>
        </tr>
            <?php
            foreach ($_SERVER as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
        
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_GET</td>
        </tr>
            <?php
            foreach ($_GET as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_POST</td>
        </tr>
            <?php
            foreach ($_POST as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_FILES</td>
        </tr>
            <?php
            foreach ($_FILES as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_REQUEST</td>
        </tr>
            <?php
            foreach ($_REQUEST as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
            
        <tr>
            <td colspan="2" style="font-weight: bold; text-align: center; background-color: #6dc4b3">$_ENV</td>
        </tr>
        
            <?php
            foreach ($_ENV as $key => $value) {
            ?>
                <tr>
                    <td style="background-color:#7be4cf;"><?php echo $key?></td>
                    <td style="background-color:#d6d6d6;"><?php echo $value?></td>
                </tr>
            <?php
            }
            ?>
        </tr>
    </table> 
        <?php
        phpinfo();
        ?>
    </body>
</html>