<?php
        /**
            *@author: Cristina Núñez
            *@since: 26/11/2020
        */ 
            
        require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 
        
            try {
                $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones
                
                $sql = <<<EOD
                        INSERT INTO T02_Departamento(T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenNegocio) VALUES
                            ('INF', 'Departamento de informatica',1606156754, 5),
                            ('VEN', 'Departamento de ventas',1606156754, 8),
                            ('CON', 'Departamento de contabilidad',1606156754, 9),
                            ('MAT', 'Departamento de matematicas',1606156754, 8),
                            ('MKT', 'Departamento de marketing',1606156754, 1);
                        
                        INSERT INTO T01_Usuario(T01_CodUsuario, T01_DescUsuario, T01_Password) VALUES
                            ('nereaa','Nerea Alvarez',SHA2('nereaapaso',256)),
                            ('miguel','Miguel Angel Aranda',SHA2('miguelpaso',256)),
                            ('bea','Beatriz Merino',SHA2('beapaso',256)),
                            ('nerean','Nerea Nuevo',SHA2('nereanpaso',256)),
                            ('cristinam','Cristina Manjon',SHA2('cristinampaso',256)),
                            ('susana','Susana Fabian',SHA2('susanapaso',256)),
                            ('sonia','Sonia Anton',SHA2('soniapaso',256)),
                            ('elena','Elena de Anton',SHA2('elenapaso',256)),
                            ('nacho','Nacho del Prado',SHA2('nachopaso',256)),
                            ('raul','Raul Nuñez',SHA2('raulpaso',256)),
                            ('luis','Luis Puente',SHA2('luispaso',256)),
                            ('arkaitz','Arkaitz Rodriguez',SHA2('arkaitzpaso',256)),
                            ('rodrigo','Rodrigo Robles',SHA2('rodrigopaso',256)),
                            ('javier','Javier Nieto',SHA2('javierpaso',256)),
                            ('cristinan','Cristina Nuñez',SHA2('cristinanpaso',256)),
                            ('heraclio','Heraclio Borbujo',SHA2('heracliopaso',256)),
                            ('amor','Amor Rodriguez',SHA2('amorpaso',256)),
                            ('antonio','Antonio Jañez',SHA2('antoniopaso',256)),
                            ('leticia','Leticia Nuñez',SHA2('leticiapaso',256));
EOD;
                
                $miDB->exec($sql);
                
                echo "<h3> <span style='color: green;'>"."Valores insertados</span></h3>";//Si no se ha producido ningún error nos mostrará "Conexión establecida con éxito"
            }
            catch (PDOException $excepcion) {//Código que se ejecutará si se produce alguna excepción
                $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
                $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion
                
                echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
                echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
            } finally {
                unset($miDB);
            }
?>