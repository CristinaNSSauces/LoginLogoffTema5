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
    
    $aIdiomas['es']=['saludo' => 'Hola',
                     'idiomaElegido' => 'Idioma: ',
                     'fecha' => 'Fecha Hora Última conexión: ',
                     'primeraConexion' => 'Es la primera vez que inicias sesión',
                     'conexiones' => 'Número de conexiones: ',
                     'detalles' => 'Detalles',
                     'editarPerfil' => 'Editar perfil',
                     'cerrarSesion' => 'Cerrar Sesión'];
    
    $aIdiomas['en']=['saludo' => 'Hello',
                     'idiomaElegido' => 'Language: ',
                     'fecha' => 'Date Time Last connection: ',
                     'primeraConexion' => 'It is the first time you log in',
                     'conexiones' => 'Number of connections: ',
                     'detalles' => 'Details',
                     'editarPerfil' => 'Edit profile',
                     'cerrarSesion' => 'logoff'];

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

    try{
        $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

        $sql = "Select T01_NumConexiones, T01_DescUsuario, T01_ImagenUsuario from T01_Usuario where T01_CodUsuario=:CodUsuario";
        $consulta = $miDB->prepare($sql);//Preparamos la consulta
        $parametros = [":CodUsuario" => $_SESSION['usuarioDAW215LoginLogoffTema5']];

        $consulta->execute($parametros);//Ejecutamos la consulta
        $registro = $consulta->fetchObject();//Obtenemos el primer registro de la consulta

        $nConexiones=$registro->T01_NumConexiones;//Guardamos el número de conexiones del usuario en $nConexiones
        $descUsuario=$registro->T01_DescUsuario;//Guardamos la descripcion del usuario
        $imagenUsuario=$registro->T01_ImagenUsuario;//Guardamos la descripcion del usuario

    }catch(PDOException $excepcion){
        $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
        $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

        echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
        echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
    } finally {
       unset($miDB); //cerramos la conexion con la base de datos
    }
    
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
            <?php
            if($imagenUsuario!=null){
                echo '<img style="margin-rigth: 2px;" src = "data:image/png;base64,' . base64_encode($imagenUsuario) . '" width = "50px"/>';
            }
            ?>
            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['editarPerfil']; ?>" name="editarPerfil" id="editarPerfil">
            <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['cerrarSesion'] ?>" name="salir" id="cerrarSesion">
        </form>
    </header>
    <main class="mainEditar">
        <div class="contenido">
            <br><br>
                    <h3><?php echo $aIdiomas[$_COOKIE['idioma']]['saludo']." ".$descUsuario;//Mostramos el saludo en el idioma correspondiente?></h3>
                    <?php
                        if($nConexiones==1){//Si es la primera vez que inicia sesion
                            ?>
                            <h3><?php echo $aIdiomas[$_COOKIE['idioma']]['primeraConexion']; ?></h3>
                    <?php
                        }else{//Si no es la prinera vez que inicias sesion
                            ?>
                            <h3><?php echo $aIdiomas[$_COOKIE['idioma']]['conexiones'].$nConexiones ?></h3>
                            <h3><?php echo $aIdiomas[$_COOKIE['idioma']]['fecha'].date('d/m/Y H:i:s',$_SESSION['FechaHoraUltimaConexionAnterior']);?> </h3>
                    <?php
                            
                        }
                    ?> 
                            
                    <h3><?php echo $aIdiomas[$_COOKIE['idioma']]['idiomaElegido'].$_COOKIE['idioma']; //Mostramos el idioma seleccionado?></h3>
            <form class="formularioPrograma" name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <input type="submit" value="<?php echo $aIdiomas[$_COOKIE['idioma']]['detalles']; ?>" name="detalles" class="aceptar">
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
