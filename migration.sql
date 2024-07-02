CREATE TABLE filiais (
    id INT NOT NULL AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cnpj VARCHAR(50) NOT NULL,
    ie VARCHAR(50) NOT NULL,
    uf VARCHAR(2) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE mdfes (
    id INT NOT NULL AUTO_INCREMENT,
    chave VARCHAR(300) NOT NULL,
    protocolo VARCHAR(300) NOT NULL,
    filial_id INT NOT NULL,
    cod_municipio VARCHAR(50) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT('pendente'),
    PRIMARY KEY (id),
    FOREIGN KEY (filial_id) REFERENCES filiais(id)
);

INSERT INTO filiais (nome, cnpj, ie, uf) VALUES ('Razao Social Matriz', '13685789000103', '52951997575', 'MT'), ('Razao Social Filial', '22458473000170', '49369203090', 'MT');

