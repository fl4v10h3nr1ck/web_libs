INSERT INTO usuarios (usuario, email, data_cadastro, senha, status)  VALUES ('admin', 'admin@admin.com.br', '2017-03-09', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1);


INSERT INTO grupos (nome, cod, data_criacao) VALUES ('ADMINS', 'ADMINS', '2017-03-17');


INSERT INTO usuarios_x_grupos (fk_usuario, fk_grupo)  VALUES (1,1);