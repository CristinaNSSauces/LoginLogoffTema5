<?php
    /**
        *@author: Cristina Núñez
        *@since: 17/11/2020
    */
    
    if(!isset($_COOKIE['idioma'])){
        setcookie("idioma", 'es', time()+2592000);//Ponemos que el idioma sea español
        header('Location: login.php');
        exit;
    }

    if(isset($_REQUEST['es'])){
        setcookie("idioma", $_REQUEST['es'], time()+2592000);//Ponemos que el idioma sea español
        header('Location: login.php');
        exit;
    }else if(isset($_REQUEST['en'])){
        setcookie("idioma", $_REQUEST['en'], time()+2592000);//Ponemos que el idioma sea ingles
        header('Location: login.php');
        exit;
    }
    
    if($_COOKIE['idioma']=='es'){
        $saludo="Bienvenido";
        $usuarioIdioma="Usuario: ";
        $passwordIdioma="Contraseña: ";
        $registrarseIdioma="Registrarse";
        $iniciarSesionIdioma="Iniciar Sesión";
    }else{
        $saludo="Welcome";
        $usuarioIdioma="User: ";
        $passwordIdioma="Password: ";
        $registrarseIdioma="Register";
        $iniciarSesionIdioma="Log in";
    }
    
    require_once '../core/libreriaValidacion.php';//Incluimos la librería de validación para comprobar los campos del formulario
    require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 

    //declaracion de variables universales
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);
    $entradaOK = true;


    //Declaramos el array de errores y lo inicializamos a null
    $aErrores = ['CodUsuario' => null,
                 'Password' => null];

    if(isset($_REQUEST['aceptar'])){ //Comprobamos que el usuario haya enviado el formulario
        $aErrores['CodUsuario'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['CodUsuario'], 15, 3, OBLIGATORIO);
        $aErrores['Password'] = validacionFormularios::validarPassword($_REQUEST['Password'], 8, 3, 1, OBLIGATORIO);
        try{//validamos que la CodUsuario sea correcta
            $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

            $sqlUsuario = "Select T01_Password from T01_Usuario where T01_CodUsuario=:CodUsuario";
            $consultaUsuario = $miDB->prepare($sqlUsuario);//Preparamos la consulta
            $parametrosUsuario = [":CodUsuario" => $_REQUEST['CodUsuario']];

            $consultaUsuario->execute($parametrosUsuario);//Pasamos los parámetros a la consulta
            $registro = $consultaUsuario->fetchObject();
            
            if($consultaUsuario->rowCount()>0){//Si la consulta devuelve algun registro el codigo del usuario es correcto
                $passwordEncriptado=hash("sha256", ($_REQUEST['CodUsuario'].$_REQUEST['Password']));
                if($passwordEncriptado!=$registro->T01_Password){//Comprobamos que la contraseña sea correcta
                    $aErrores['CodUsuario'] = "Error autentificacion";//Si la contraseña no es correcta guardamos un mensaje de error en el array de errores
                    $aErrores['Password'] = "Error autentificacion";//Si la contraseña no es correcta guardamos un mensaje de error en el array de errores
                }
            }else{//Si la consulta no devuelve ningun registro el codigo del usuario no es correcto
                $aErrores['CodUsuario'] = "Error autentificacion";//Almacenamos un mensaje de error en el array de errores
                $aErrores['Password'] = "Error autentificacion";//Almacenamos un mensaje de error en el array de errores
            }
            
        }catch(PDOException $excepcion){
            $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
            $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

            echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
            echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
        } finally {
           unset($miDB); //cerramos la conexion con la base de datos
        }
        
        // Recorremos el array de errores
        foreach ($aErrores as $campo => $error){
            if ($error != null) { // Comprobamos que el campo no esté vacio
                $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario
                $_REQUEST[$campo]="";//Limpiamos los campos del formulario
            }
        }
    }else{
        $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
    }
    if($entradaOK){ // Si el usuario ha rellenado el formulario correctamente rellenamos el array aFormulario con las respuestas introducidas por el usuario
        try{//validamos que la CodUsuario sea correcta
            $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

            $sql = "Select T01_NumConexiones, T01_FechaHoraUltimaConexion from T01_Usuario where T01_CodUsuario=:CodUsuario";
            $consulta = $miDB->prepare($sql);//Preparamos la consulta
            $parametros = [":CodUsuario" => $_REQUEST['CodUsuario']];

            $consulta->execute($parametros);//Ejecutamos la consulta
            $registro = $consulta->fetchObject();//Obtenemos el primer registro de la consulta

            $nConexiones = $registro->T01_NumConexiones;//Almacenamos el numero de conexiones almacenado en la base de datos
            $fechaHoraUltimaConexion = $registro->T01_FechaHoraUltimaConexion;//Almacenamos la fecha hora de la ultima conexion almacenada en la base de datos

            settype($nConexiones, "integer");//Convertimos en entero el numero de conexiones devualto por la consulta
            
            $sqlUpdate = "Update T01_Usuario set T01_NumConexiones = :NumConexiones, T01_FechaHoraUltimaConexion=:FechaHoraUltimaConexion where T01_CodUsuario=:CodUsuario";
            $consultaUpdate = $miDB->prepare($sqlUpdate);//Preparamos la consulta
            $parametrosUpdate = [":NumConexiones" => ($nConexiones+1),
                                 ":FechaHoraUltimaConexion" => time(),
                                 ":CodUsuario" => $_REQUEST['CodUsuario']];
            $consultaUpdate->execute($parametrosUpdate);//Pasamos los parámetros a la consulta

            session_start();//Iniciamos la sesión
            $_SESSION['usuarioDAW215LoginLogoffTema5']=$_REQUEST['CodUsuario'];//Almacenamos en una variable de sesión el codigo del usuario
            $_SESSION['FechaHoraUltimaConexionAnterior']=$fechaHoraUltimaConexion;//Almacenamos la fecha hora de la ultima conexion en una variable de sesion
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
        <div class="logo">Login Logoff Tema 5</div>
        <form name="formularioIdioma" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioAlta">
            <button type="submit" name="es" value="es" style="background-color: transparent; border: 0px;"><img src="../webroot/media/español.png" width="35px"></button>
            <button type="submit" name="en" value="en" style="background-color: transparent; border: 0px;"><img src="../webroot/media/ingles.png" width="35px"></button>
        </form>
    </header>
    <main class="mainEditar">
        <div class="contenido">
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioAlta">
                <h3 style="text-align: center;"><?php echo $saludo; ?></h3>
                <br>
                <div>
                    <label style="font-weight: bold;" class="CodigoDepartamento" for="CodUsuario"><?php echo $usuarioIdioma; ?></label>
                    <input type="text" style="background-color: #D2D2D2" id="CodUsuario" name="CodUsuario" value="<?php echo(isset($_REQUEST['CodUsuario']) ? $_REQUEST['CodUsuario'] : null); ?>">
                   
                    <br><br>

                    <label style="font-weight: bold;" class="DescripcionDepartamento" for="Password"><?php echo $passwordIdioma; ?></label>
                    <input type="password" style="background-color: #D2D2D2" id="DescDepartamento" name="Password" value="<?php echo(isset($_REQUEST['Password']) ? $_REQUEST['Password'] : null);?>">
                    
                    <br><br>
                </div>
                <div>
                    <input type="submit" style="background-color: #f0dd7f;" value="<?php echo $iniciarSesionIdioma; ?>" name="aceptar" class="aceptar">
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