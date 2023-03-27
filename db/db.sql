CREATE TABLE Ruteo (
    idRuteo INT NOT NULL AUTO_INCREMENT,
    fecha DATE NOT NULL,
    Repartidor VARCHAR(50) NOT NULL,
    idRuta INT NOT NULL,
    PRIMARY KEY (idRuteo)
);

CREATE TABLE Paradas (
    idParada INT NOT NULL AUTO_INCREMENT,
    idRuta INT NOT NULL,
    fecha DATE NOT NULL,
    idEstatus INT NOT NULL,
    latitud DECIMAL(10, 8) NOT NULL,
    longitud DECIMAL(11, 8) NOT NULL,
    comentarios TEXT,
    total DECIMAL(10, 2),
    cliente VARCHAR(50) NOT NULL,
    PRIMARY KEY (idParada),
    FOREIGN KEY (idRuta) REFERENCES Ruteo(idRuteo)
);
