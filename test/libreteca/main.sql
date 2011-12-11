CREATE DATABASE `libreteca` ;

CREATE TABLE `libros` (
  `id` INT NOT NULL ,
  `artist` VARCHAR( 255 ) NOT NULL ,
  `name` VARCHAR( 255 ) NOT NULL ,
  `genre` INT NOT NULL ,
  `publi` DATE NOT NULL ,
  `cover` VARCHAR( 255 ) NOT NULL ,
  `path` VARCHAR( 255 ) NOT NULL ,
  `rate` INT NOT NULL ,
  `timstp` DATE NOT NULL ,
  `usrid` VARCHAR(100) NOT NULL,
  PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;

CREATE TABLE `generos` (
  `genre` INT NOT NULL ,
  `name` VARCHAR( 255 ) NOT NULL ,
  PRIMARY KEY ( `genre` )
) ENGINE = MYISAM ;

-- Los datos en el campo libros!path van a partir del path relativo:

CREATE TABLE `users` (
`nomb` VARCHAR(100) NOT NULL,
`pass` VARCHAR(100) NOT NULL,
`mail` VARCHAR(100) NOT NULL,
`avat` VARCHAR(100) NOT NULL,
`coun` VARCHAR(100) NOT NULL, -- Pais de origen
`nive` TINYINT NOT NULL,  -- -1 es temporal
`ahas` VARCHAR(999) NOT NULL, -- Activation Hash
PRIMARY KEY (`mail`)
);

CREATE TABLE `tags` (
`id` INT NOT NULL ,
`orde` INT NOT NULL ,
`tag` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` , `orde` )
) ENGINE = MYISAM ;
