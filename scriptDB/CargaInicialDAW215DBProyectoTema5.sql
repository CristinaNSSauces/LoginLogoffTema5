-- La contraseña de los usuarios, es el codUsuario concatenado con el password, en este caso paso. [$usuario . $pass]
-- Base de datos a usar
USE DAW215DBProyectoTema5;

-- Introduccion de datos dentro de la tabla creada
INSERT INTO T02_Departamento(T02_CodDepartamento, T02_DescDepartamento, T02_FechaCreacionDepartamento, T02_VolumenNegocio) VALUES
    ('INF', 'Departamento de informatica',1606156754, 5),
    ('VEN', 'Departamento de ventas',1606156754, 8),
    ('CON', 'Departamento de contabilidad',1606156754, 9),
    ('MAT', 'Departamento de matematicas',1606156754, 8),
    ('MKT', 'Departamento de marketing',1606156754, 1);
-- 1606156754 -> 23-nov-2020 ~19:39:14 --
-- El tipo de usuario es "usuario" como predeterminado, despues añado un admin --
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

-- Usuario con el rol admin --
INSERT INTO T01_Usuario(T01_CodUsuario, T01_DescUsuario, T01_Password, T01_Perfil) VALUES ('admin','admin',SHA2('adminpaso',256), 'administrador');
