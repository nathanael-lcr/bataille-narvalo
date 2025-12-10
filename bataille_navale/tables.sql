CREATE TABLE grille (
    id INT PRIMARY KEY AUTO_INCREMENT,
    x INT,
    y INT,
	id_joueur INT,
    id_bateau INT,
    FOREIGN KEY (id_joueur) REFERENCES joueur(id),
	FOREIGN KEY (id_bateau) REFERENCES bateau(id)
);

CREATE TABLE bateau(
	id INT PRIMARY KEY AUTO_INCREMENT,
	size INT, -- 0=vide, 2=destroyer, 3=sous-marin, 4=croiseur, 5=porte-avion
	name VARCHAR(25)
);

CREATE TABLE joueur(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(25)
);

 





