INSERT INTO utente (username, password, nome, attivo)
VALUES
('maurizio.m', 'password123', 'Maurizio Merluzzo', 1),
('marco.m', 'password123', 'Marco Merrino', 1);

INSERT INTO post (titolopost, testopost, datapost, anteprimapost, imgpost, likes, utente) VALUES
('Cercasi insegnante di inglese', 'Sto cercando un insegnante di inglese per lezioni private a domicilio. Contattatemi se interessati!', '2025-12-18', 'Sto cercando un insegnante di inglese per lezioni private a domicilio...', 'default.jpg', 5, 1),
('Sciarpa persa', 'Ho perso una sciarpa rossa con motivi floreali. Se qualcuno la trovasse, per favore contattatemi.', '2025-12-18', 'Ho perso una sciarpa rossa con motivi floreali. Se qualcuno la trovasse...', 'frog.jpg', 3, 2);

INSERT INTO tag (nometag) VALUES
('Eventi'),
('Compro/Vendo'),
('Casa/Affitti'),
('Lavoro'),
('Libri/Appunti'),
('Gruppi Uni'),
('Palestra'),
('Regole'),
('Meme');

INSERT INTO post_tag (post, tag) VALUES
(1, 4),
(2, 2);

INSERT INTO commento (testocommento, datacommento, nomeautore, post) VALUES
('Ciao, io sono disponibile per lezioni di inglese!', '2025-12-19 10:30:00', 'Pinco Pallino', 1),
('Che livello cerchi?', '2025-12-19 14:15:00', 'Giovanni Rossi', 1),
('Ho trovato una sciarpa simile vicino alla biblioteca, potrebbe essere quella.', '2025-12-20 09:00:00', 'Michele Savi', 2),
('Di che marca era?', '2025-12-20 11:45:00', 'Laura Bianchi', 2);
INSERT INTO user_likes (utente, post) VALUES
(1, 1),
(2, 1),
(1, 2);
