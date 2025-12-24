INSERT INTO utente (username, password, nome, attivo)
VALUES
('maurizio.m', 'password123', 'Maurizio Merluzzo', 1),
('marco.m', 'password123', 'Marco Merrino', 1);

INSERT INTO post (titolopost, testopost, datapost, anteprimapost, imgpost, utente) VALUES
('Cercasi insegnante di inglese', 'Sto cercando un insegnante di inglese per lezioni private a domicilio. Contattatemi se interessati!', '2025-12-18', 'Sto cercando un insegnante di inglese per lezioni private a domicilio...', 'default.jpg', 1),
('Sciarpa persa', 'Ho perso una sciarpa rossa con motivi floreali. Se qualcuno la trovasse, per favore contattatemi.', '2025-12-18', 'Ho perso una sciarpa rossa con motivi floreali. Se qualcuno la trovasse...', 'default.jpg', 2);