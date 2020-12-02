<?php
    /**
        *@author: Cristina Núñez
        *@since: 26/11/2020
    */
    session_start();//reanudamos la sesion existente
    
    if (!isset($_SESSION['usuarioDAW215LoginLogoffTema5'])) {//Si el usuario no se ha autentificado
        header('Location: login.php');//Redirigimos al usuario al ejercicio01.php para que se autentifique
        exit;
    }
    
    if($_COOKIE['idioma']=='es'){
        $saludo="Hola";
        $fechaIdioma="Fecha Hora Última conexión: ";
        $primeraConexionIdioma="Es la primera vez que inicias sesión";
        $conexionesIdioma="Número de conexiones: ";
        $detallesIdioma="Detalles";
        $editarPerfilIdioma="Editar perfil";
        $cerrarSesionIdioma="Cerrar Sesión";
    }else{
        $saludo="Hello";
        $fechaIdioma="Date Time Last connection: ";
        $primeraConexionIdioma="It is the first time you log in";
        $conexionesIdioma="Number of connections: ";
        $detallesIdioma="Details";
        $editarPerfilIdioma="Edit profile";
        $cerrarSesionIdioma="logoff";
    }
    
    if(isset($_REQUEST['es'])){
        setcookie("idioma", $_REQUEST['es'], time()+2592000);//Ponemos que el idioma sea español
        $saludo="Hola";
        header('Location: programa.php');
        exit;
    }else if(isset($_REQUEST['en'])){
        setcookie("idioma", $_REQUEST['en'], time()+2592000);//Ponemos que el idioma sea ingles
        $saludo="Hello";
        header('Location: programa.php');
        exit;
    }
    
    if(isset($_REQUEST['detalles'])){
        header('Location: detalles.php');
        exit;
    }
    
    if(isset($_REQUEST['editarPerfil'])){
        header('Location: editarPerfil.php');
        exit;
    }
    
    if(isset($_REQUEST['salir'])){
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
    require_once '../core/libreriaValidacion.php';//Importamos la librería de validación para validar los campos del formulario necesarios
    require_once '../config/confDBPDO.php';
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa</title>
    <link href="../webroot/css/style.css" rel="stylesheet"> 
</head>
<body>
    <header>
        <div class="logo">Programa</div>
        <form name="formularioIdioma" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioIdioma">
            <button type="submit" name="es" value="es" style="background-color: transparent; border: 0px;"><img src="../webroot/media/español.png" width="35px"></button>
            <button type="submit" name="en" value="en" style="background-color: transparent; border: 0px;"><img src="../webroot/media/ingles.png" width="35px"></button>
            <input type="submit" value="<?php echo $cerrarSesionIdioma ?>" name="salir" id="cerrarSesion">
        </form>
    </header>
    <main class="mainEditar">
        <div class="contenido">
        <?php
            try{
                $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

                $sql = "Select T01_NumConexiones, T01_DescUsuario from T01_Usuario where T01_CodUsuario=:CodUsuario";
                $consulta = $miDB->prepare($sql);//Preparamos la consulta
                $parametros = [":CodUsuario" => $_SESSION['usuarioDAW215LoginLogoffTema5']];

                $consulta->execute($parametros);//Ejecutamos la consulta
                $registro = $consulta->fetchObject();//Obtenemos el primer registro de la consulta
                
                $nConexiones=$registro->T01_NumConexiones;//Guardamos el número de conexiones del usuario en $nConexiones
                $descUsuario=$registro->T01_DescUsuario;//Guardamos la descripcion del usuario
                
            }catch(PDOException $excepcion){
                $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
                $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

                echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
                echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
            } finally {
               unset($miDB); //cerramos la conexion con la base de datos
            }
            
            //if(isset($_COOKIE['idioma']) && isset($_COOKIE['saludo'])){//Comprobamos que existe $_COOKIE['idioma'] y ($_COOKIE['saludo']
            ?>
    
                    <h3><?php echo $saludo." ".$descUsuario; //Mostramos el saludo en el idioma correspondiente?></h3>
                    <?php
                        if($nConexiones==1){//Si es la primera vez que inicia sesion
                            ?>
                            <h3><?php echo $primeraConexionIdioma; ?></h3>
                    <?php
                        }else{//Si no es la prinera vez que inicias sesion
                            ?>
                            <h3><?php echo $conexionesIdioma.$nConexiones ?></h3>
                            <h3><?php echo $fechaIdioma.date('d/m/Y H:i:s',$_SESSION['FechaHoraUltimaConexionAnterior']);?> </h3>
                    <?php
                            
                        }
                    ?>                    
        <?php
                if($_COOKIE['idioma']=='es'){//Si el idioma almacenado en la cookie idioma es español
            ?>  
                    <h3>Idioma: <?php echo $_COOKIE['idioma']; //Mostramos el idioma seleccionado en español?></h3>
            <?php
                }
                if($_COOKIE['idioma']=='en'){//Si el idioma almacenado en la cookie idioma es ingles
            ?>   
                    <h3>Language: <?php echo $_COOKIE['idioma']; //Mostramos el idioma seleccionado en ingles?></h3>
            <?php
                }
                if($_COOKIE['idioma']=='fr'){//Si el idioma almacenado en la cookie idioma es francés
            ?>   
                    <h3>Langage: <?php echo $_COOKIE['idioma']; //Mostramos el idioma seleccionado en francés?></h3>
            <?php
                }
            //}
        ?>
            <form class="formularioPrograma" name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <input type="submit" value="<?php echo $detallesIdioma; ?>" name="detalles" class="aceptar">
            </form>
        </div>
    </main>
    <footer> 
        <table class="tablaFooter">
            <tr> 
                <td><a href="http://daw215.ieslossauces.es/" target="_blank"><img src="../webroot/media/1&1.png" alt="1&1" width="45"></a></td>
                <td style="font-size: 26px;"><a href="#">Cristina Núñez Sebastián</a></td>
                <td><a href="https://github.com/CristinaNSSauces" target="_blank"><img src="../webroot/media/git.png" alt="git" width="45"></a></td>
            </tr>
        </table>
    </footer>
</body>
</html>
