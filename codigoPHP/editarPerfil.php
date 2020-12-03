<?php
    /**
        *@author: Cristina Núñez
        *@since: 02/12/2020
    */
    session_start();
    
    if (!isset($_SESSION['usuarioDAW215LoginLogoffTema5'])) {//Si el usuario no se ha autentificado
        header('Location: login.php');//Redirigimos al usuario al ejercicio01.php para que se autentifique
        exit;
    }
    
    if(isset($_REQUEST['salir'])){
        session_destroy();
        header('Location: login.php');
        exit;
    }
    
    if(isset($_REQUEST['cancelar'])){
        header('Location: programa.php');
        exit;
    }
   
    if($_COOKIE['idioma']=='es'){
        $saludo="Bienvenido";
        $usuarioIdioma="Usuario: ";
        $descripcionIdioma="Descripción: ";
        $fechaIdioma="Fecha Hora Última conexión: ";
        $conexionesIdioma="Número de conexiones: ";
        $passwordIdioma="Contraseña: ";
        $cambiarPasswordIdioma="Cambiar Contraseña";
        $cerrarSesionIdioma="Cerrar Sesión";
        $aceptarIdioma="Aceptar";
        $cancelarIdioma="Cancelar";
    }else{
        $saludo="Welcome";
        $usuarioIdioma="User: ";
        $descripcionIdioma="Description: ";
        $fechaIdioma="Date Time Last connection: ";
        $conexionesIdioma="Number of connections: ";
        $passwordIdioma="Password: ";
        $cambiarPasswordIdioma="Change Password";
        $cerrarSesionIdioma="logoff";
        $aceptarIdioma="Acept";
        $cancelarIdioma="Cancel";
    }
    
    require_once '../core/libreriaValidacion.php';//Incluimos la librería de validación para comprobar los campos del formulario
    require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 
    
    try{//validamos que la CodUsuario sea correcta
        $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

        $sql = "Select T01_DescUsuario, T01_NumConexiones from T01_Usuario where T01_CodUsuario=:CodUsuario";
        $consulta = $miDB->prepare($sql);//Preparamos la consulta
        $parametros = [":CodUsuario" => $_SESSION['usuarioDAW215LoginLogoffTema5']];

        $consulta->execute($parametros);//Ejecutamos la consulta
        $registro = $consulta->fetchObject();
        
        $descripcionUsuario=$registro->T01_DescUsuario;
        $numConexiones=$registro->T01_NumConexiones;

    }catch(PDOException $excepcion){
        $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
        $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

        echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
        echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
    } finally {
       unset($miDB); //cerramos la conexion con la base de datos
    }
    //declaracion de variables universales
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);
    $entradaOK = true;


    //Declaramos el array de errores y lo inicializamos a null
    $errorDescripcion = "";
    $errorImagen = "";

    if(isset($_REQUEST['aceptar'])){ //Comprobamos que el usuario haya enviado el formulario
        $errorDescripcion = validacionFormularios::comprobarAlfaNumerico($_REQUEST['Descripcion'], 255, 3, OBLIGATORIO);

        if($_FILES['imagen']['tmp_name']!=null){
            $tipo = $_FILES['imagen']['type'];
            if (($tipo == "image/gif") || ($tipo == "image/jpeg") || ($tipo == "image/jpg") || ($tipo == "image/png")){
                $imagenSubida = file_get_contents($_FILES['imagen']['tmp_name']);
                
            }else{
                $errorImagen="formato incorrecto";
            }
        }
        // Recorremos el array de errores
        if ($errorDescripcion != null) { // Comprobamos que el campo no esté vacio
            $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario
            $_REQUEST['Descripcion']="";//Limpiamos los campos del formulario
        }
        
        if ($errorImagen != null) { // Comprobamos que el campo no esté vacio
            $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario
        }
    }else{
        $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
    }
    if($entradaOK){ // Si el usuario ha rellenado el formulario correctamente rellenamos el array aFormulario con las respuestas introducidas por el usuario
        try{//validamos que la CodUsuario sea correcta
            $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

            $sql = "Update T01_Usuario set T01_DescUsuario = :DescUsuario where T01_CodUsuario=:CodUsuario";
            $consulta = $miDB->prepare($sql);//Preparamos la consulta
            $parametros = [":DescUsuario" => $_REQUEST['Descripcion'],
                           ":CodUsuario" => $_SESSION['usuarioDAW215LoginLogoffTema5']];

            $consulta->execute($parametros);//Ejecutamos la consulta
            
            if($imagenSubida!=null){
                $sqlImagen = "Update T01_Usuario set T01_ImagenUsuario = :Imagen where T01_CodUsuario=:CodUsuario";
                $consultaImagen = $miDB->prepare($sqlImagen);//Preparamos la consulta
                $parametrosImagen = [":Imagen" => $imagenSubida,
                                     ":CodUsuario" => $_SESSION['usuarioDAW215LoginLogoffTema5']];

                $consultaImagen->execute($parametrosImagen);//Ejecutamos la consulta
            }
            
            header('Location: programa.php');
            exit;
            
        }catch(PDOException $excepcion){
            $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
            $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

            echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
            echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
        } finally {
           unset($miDB); //cerramos la conexion con la base de datos
        }
    }else{//Si el usuario no ha rellenado el formulario correctamente volvera a rellenarlo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="../webroot/css/style.css" rel="stylesheet"> 
</head>
<body>
    <header>
        <div class="logo">Editar Perfil</div>
    </header>
    <main class="mainEditar">
        <div class="contenido">
            <h3 style="text-align: center;"><?php echo $saludo; ?></h3>
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioAlta" enctype="multipart/form-data">
                    
                <div>
                    <label style="font-weight: bold;" class="CodigoDepartamento" for="CodUsuario"><?php echo $usuarioIdioma; ?></label>
                    <input type="text" style="background-color: transparent; border: 0px;" id="CodUsuario" name="CodUsuario" value="<?php echo $_SESSION['usuarioDAW215LoginLogoffTema5']; ?>" readonly>

                    <br><br>
                    
                    <label style="font-weight: bold;" class="CodigoDepartamento" for="Descripcion"><?php echo $descripcionIdioma; ?></label>
                    <input type="text" style="background-color: #D2D2D2" id="Descripcion" name="Descripcion" value="<?php echo(isset($_REQUEST['Descripcion']) ? $_REQUEST['Descripcion'] : $descripcionUsuario); ?>">
                    <?php
                        if ($errorDescripcion != null) { // Si hay algun mensaje de error almacenado en el array para este campo del formulario se lo mostramos al usuario por pantalla al lado del campo correspondiente
                            echo "<span style='color: red;'>".$errorDescripcion."</span>";
                        }
                    ?>
                    <br><br>
                    
                    <label style="font-weight: bold;" class="CodigoDepartamento" for="NConexiones"><?php echo $conexionesIdioma; ?></label>
                    <input type="text" style="background-color: transparent; border: 0px;" id="NConexiones" name="NConexiones" value="<?php echo $numConexiones; ?>" readonly>

                    <br><br>
                    <?php
                        if($numConexiones>1){
                    ?>
                        <label style="font-weight: bold;" class="CodigoDepartamento" for="FechaHora"><?php echo $fechaIdioma; ?></label>
                        <input type="text" style="background-color: transparent; border: 0px;" id="FechaHora" name="FechaHora" value="<?php echo date('d/m/Y H:i:s',$_SESSION['FechaHoraUltimaConexionAnterior']);?>" readonly>

                        <br><br>
                    <?php
                        }
                    ?>
                    <label style="font-weight: bold;" class="CodigoDepartamento" for="imagen">Imagen: </label>
                    <input type="file" id="imagen" name="imagen">
                    <?php
                        if ($errorImagen != null) { // Si hay algun mensaje de error almacenado en el array para este campo del formulario se lo mostramos al usuario por pantalla al lado del campo correspondiente
                            echo "<span style='color: red;'>".$errorImagen."</span>";
                        }
                    ?>
                    <br><br>
                    <a href="cambiarPassword.php"><p style="color: blue;font-family: 'News Cycle', sans-serif; font-weight: bold; font-size: 17px;"><?php echo $cambiarPasswordIdioma; ?></p></a>
                    <br>
                </div>
                <div>
                    <input type="submit" value="<?php echo $aceptarIdioma; ?>" name="aceptar" class="aceptar">
                    <input type="submit" style="background-color: #ff8787;" value="<?php echo $cancelarIdioma; ?>" name="cancelar" class="aceptar">
                </div>
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
<?php
    }
?>