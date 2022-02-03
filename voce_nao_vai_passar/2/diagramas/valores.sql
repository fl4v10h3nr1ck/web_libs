

INSERT INTO filiais(codigo, nome, endereco, tel_1, 	tel_2, 	email, site, status) VALUES 
('MATRIZ', 'UNIDADE MATRIZ', 'Rua sem nome N 123, sacramenta belem-PA', '(91) 99293-4370', null, "matriz@matriz.com", null, 1);


INSERT INTO usuarios (usuario, email, data_cadastro, senha, status, conectado) 
VALUES ('admin', 'admin@admin.com.br', '2017-03-09', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92', 1, 1);

INSERT INTO usuarios_x_filiais (fk_usuario, 	fk_filial, 	padrao) 
VALUES (1, 1, 1);


INSERT INTO grupos (nome, cod, data_criacao, status, admin, fk_filial) VALUES ('ADMINS', 'ADMINS', '2017-03-17', 1, 1, 1);


INSERT INTO usuarios_x_grupos (fk_usuario, fk_grupo)  VALUES (1,1);



INSERT INTO acessos (cod, nome, tipo, ordem) VALUES 
('GSSUSR', 'Gestão de sua conta de usuário pessoal', 'VER_ED_REM', 1),
('GSSFIL', 'Gestão de suas filiais', 'SIM_NAO', 1),
('GSSCAR', 'Gestão de seus cargos', 'SIM_NAO', 1),

('GEGUSR', 'Gestão geral de contas de usuários', 'VER_ED_REM', 2),
('GEGFIL', 'Gestão geral de filiais de usuários', 'SIM_NAO', 2),
('GEGCAR', 'Gestão geral de cargos de usuários', 'SIM_NAO', 2),

('GESFIL', 'Gestão de filiais', 'VER_ED_REM', 3),
('GESCAR', 'Gestão de cargos', 'VER_ED_REM', 3),
('GESDEP', 'Gestão de departamentos', 'VER_ED_REM', 3),

('GEGGRP', 'Gestão geral de grupos de usuários', 'VER_ED_REM', 4),
('VINUSR', 'Vincular usuários a grupos', 'SIM_NAO', 4),
('ADDPMS', 'Adicionar permissão a grupos', 'SIM_NAO', 4),

('ABRCMD', 'Abrir e incluir dados em chamados', 'SIM_NAO', 5),
('ATDCMD', 'Atender chamados', 'SIM_NAO', 5),
('ESPCMD', 'Colocar chamados em espera', 'SIM_NAO', 5),
('CCLCMD', 'Cancelar chamados', 'SIM_NAO', 5),
('FCRCMD', 'Fechar chamados', 'SIM_NAO', 5),

('GEMDOC', 'Gestão de documentos de meus departamentos', 'VER_ED_REM', 6),
('GEODOC', 'Gestão de documentos de outros departamentos', 'VER_ED_REM', 6),

('ABRCNC', 'Abrir e incluir dados em CNC', 'SIM_NAO', 7),
('DPTCNC', 'Receber CNC de meu departamento', 'SIM_NAO', 7),
('FCRCNC', 'Fechar CNC', 'SIM_NAO', 7);





