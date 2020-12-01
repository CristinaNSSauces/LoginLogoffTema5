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
    
    require_once '../core/libreriaValidacion.php';//Importamos la librería de validación para validar los campos del formulario necesarios
    require_once '../config/confDBPDO.php';
    $errorIdioma = null;//Creamos e inicializamos $errorIdioma a null, en ella almacenaremos (si hay) los errores al validar el campo idioma del formulario
    $entradaOK = true;//Creamos e inicializamos $entradaOK a true
    
    if(isset($_REQUEST['detalles']) || isset($_REQUEST['salir'])){ //Comprobamos que el usuario haya enviado el formulario
        $errorIdioma = validacionFormularios::validarElementoEnLista($_REQUEST['idioma'], ['es','en','fr']);//Validamos el elemento lista del formulario, de tener error almacenamos el mensaje en la variable $errorIdioma
        if ($errorIdioma != null) {
            $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario                             
        }         
    }else{
        $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
    }
    if($entradaOK){ // Si el usuario ha rellenado el formulario correctamente rellenamos el array aFormulario con las respuestas introducidas por el usuario
        if($_REQUEST['idioma']=='es'){//Si el idioma seleccionado por el usuario es español
            setcookie("idioma", 'es', time()+2592000);//Creamos o cambiamos la cookie idioma al valor 'es'
            setcookie('saludo','Hola', time()+2592000);//Creamos o cambiamos la cookie saludo al valor 'del idioma seleccionado por el usuario'Hola'
        }
        if($_REQUEST['idioma']=='en'){//Si el idioma seleccionado por el usuario es ingles
            setcookie("idioma", 'en', time()+2592000);//Creamos o cambiamos la cookie idioma al valor 'en'
            setcookie('saludo','Hello', time()+2592000);//Creamos o cambiamos la cookie saludo al valor 'del idioma seleccionado por el usuario'Hello'
        }
        if($_REQUEST['idioma']=='fr'){//Si el idioma seleccionado por el usuario es francés
            setcookie("idioma", 'fr', time()+2592000);//Creamos o cambiamos la cookie idioma al valor 'fr'
            setcookie('saludo','Salut', time()+2592000);//Creamos o cambiamos la cookie saludo al valor 'del idioma seleccionado por el usuario'Salut'
        }
        if(isset($_REQUEST['detalles'])){//Si pulsa el botón de detalles
            header('Location: detalles.php');//Redirigimos al usuario a la ventana de detalles
            exit;
        }

        if(isset($_REQUEST['salir'])){//Si el usuario pulsa el botón de salir
            session_destroy();
            header('Location: login.php');//Redirigimos al usuario al index del tema 5
            exit;
        }
    }
?>
<!DOCTYPE html>
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
            
            if(isset($_COOKIE['idioma']) && isset($_COOKIE['saludo'])){//Comprobamos que existe $_COOKIE['idioma'] y ($_COOKIE['saludo']
            ?>
    
                    <h3><?php echo $_COOKIE['saludo']." ".$descUsuario; //Mostramos el saludo en el idioma correspondiente?></h3>
                    <?php
                        if($nConexiones==1){
                            ?>
                            <h3>Es la primera vez que inicias sesión</h3>
                    <?php
                        }else{
                            ?>
                            <h3>Has iniciado sesion <?php echo $nConexiones ?> veces</h3>
                            <h3>Última conexión: <?php echo date('d/m/Y H:i:s',$_SESSION['FechaHoraUltimaConexion'])?> </h3>
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
            }
        ?>
           <form class="formularioPrograma" name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label for="idioma">Seleccione Idioma:</label>
                <select id="idioma" name="idioma">
                  <option value="es" <?php 
                    if(isset($_COOKIE['idioma'])){//si existe la cookie idioma
                        if($_COOKIE['idioma']=='es'){//Si el idioma almacenado es español
                            echo 'selected';//Será el valor seleccionado en nuestra lista
                        }
                    }
                    ?>>Español</option>
                  <option value="en" <?php
                    if(isset($_COOKIE['idioma'])){//si existe la cookie idioma
                        if($_COOKIE['idioma']=='en'){//Si el idioma almacenado es ingles
                            echo 'selected';//Será el valor seleccionado en nuestra lista
                        }
                    }
                    ?>>English</option>
                  <option value="fr" <?php
                    if(isset($_COOKIE['idioma'])){//si existe la cookie idioma
                        if($_COOKIE['idioma']=='fr'){//Si el idioma almacenado es frances
                            echo 'selected';//Será el valor seleccionado en nuestra lista
                        }
                    }     
                    ?>>Français</option>
                </select>
                <br><br>
                <input type="submit" value="DETALLES" name="detalles">
                <input type="submit" value="CERRAR SESIÓN" name="salir">
                <br><br>
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
