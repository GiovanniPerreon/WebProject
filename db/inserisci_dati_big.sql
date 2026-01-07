-- ==================== UTENTI ====================
INSERT INTO utente (username, password, nome, attivo, amministratore, imgprofilo) VALUES
('admin', 'admin123', 'Admin Sistema', 1, 1, 'default-avatar.png'),
('giulia.r', 'password123', 'Giulia Rossi', 1, 1, 'default-avatar.png'),
('marco.b', 'password123', 'Marco Bianchi', 1, 0, 'default-avatar.png'),
('sara.v', 'password123', 'Sara Verdi', 1, 0, 'default-avatar.png'),
('luca.m', 'password123', 'Luca Martini', 1, 0, 'default-avatar.png'),
('elena.c', 'password123', 'Elena Conti', 1, 0, 'default-avatar.png'),
('alessandro.f', 'password123', 'Alessandro Ferrari', 1, 0, 'default-avatar.png'),
('francesca.g', 'password123', 'Francesca Galli', 1, 0, 'default-avatar.png'),
('davide.p', 'password123', 'Davide Pellegrini', 1, 0, 'default-avatar.png'),
('chiara.l', 'password123', 'Chiara Lombardi', 1, 0, 'default-avatar.png'),
('matteo.s', 'password123', 'Matteo Santini', 1, 0, 'default-avatar.png'),
('maurizio.m', 'password123', 'Maurizio Merluzzo', 1, 0, 'default-avatar.png'),
('marco.m', 'password123', 'Marco Merrino', 1, 0, 'default-avatar.png'),
('michele.s', 'password123', 'Michele Savi', 1, 1, 'default-avatar.png'),
('sofia.p', 'password123', 'Sofia Parisi', 1, 0, 'default-avatar.png'),
('andrea.r', 'password123', 'Andrea Ricci', 1, 0, 'default-avatar.png');

-- ==================== TAG ====================
INSERT INTO tag (nometag) VALUES
('Eventi'),
('Compro/Vendo'),
('Casa/Affitti'),
('Lavoro'),
('Libri/Appunti'),
('Gruppi Uni'),
('Palestra'),
('Regole'),
('Meme'),
('Oggetti Smarriti'),
('Trasporti'),
('Domande');

-- ==================== POST (60 posts) ====================
INSERT INTO post (titolopost, testopost, datapost, anteprimapost, imgpost, pinned, likes, anonimo, utente) VALUES
-- Dicembre 2025
('Cercasi coinquilino zona campus', 'Ciao a tutti! Cerco un coinquilino per un appartamento in zona campus, a 5 minuti a piedi dalla facoltà di Ingegneria. Stanza singola, 280€ al mese spese incluse. L''appartamento è già arredato e ha una bella cucina. Disponibile da febbraio.', '2025-12-01', 'Cerco un coinquilino per un appartamento in zona campus...', '', 0, 12, 0, 3),
('Vendo libri Analisi 1 e Fisica', 'Vendo i seguenti libri in ottime condizioni: - Analisi Matematica 1 (Bramanti-Pagani-Salsa) 25€ - Fisica 1 (Mazzoldi) 20€ - Elementi di Algebra Lineare 15€. Prezzo trattabile se presi insieme.', '2025-12-02', 'Vendo libri in ottime condizioni: Analisi Matematica 1, Fisica 1...', '', 0, 8, 0, 4),
('Gruppo studio Basi di Dati', 'Sto cercando persone per formare un gruppo studio per l''esame di Basi di Dati. L''idea è di trovarci 2-3 volte a settimana in biblioteca per fare esercizi insieme.', '2025-12-03', 'Cerco persone per gruppo studio Basi di Dati...', '', 0, 22, 0, 6),
('Festa di fine semestre - 20 Dicembre!', '🎉 FESTA DI FINE SEMESTRE 🎉 Venerdì 20 Dicembre al Circolo Universitario! Ingresso 5€ con consumazione inclusa. DJ set, karaoke e tanti premi!', '2025-12-04', '🎉 FESTA DI FINE SEMESTRE! Venerdì 20 Dicembre...', '', 1, 45, 0, 2),
('Qualcuno ha trovato un portafoglio?', 'Ho perso il portafoglio nella zona della biblioteca centrale ieri sera tra le 18 e le 19. È di pelle marrone con le mie tessere universitarie dentro. Offro ricompensa!', '2025-12-05', 'Ho perso il portafoglio nella biblioteca centrale...', '', 0, 15, 0, 5),
('Offro ripetizioni di Programmazione', 'Sono uno studente magistrale e offro ripetizioni di Programmazione (C, Java, Python) a studenti del primo e secondo anno. 15€/ora, possibilità di lezioni online o in presenza.', '2025-12-06', 'Offro ripetizioni di Programmazione: C, Java, Python...', '', 0, 18, 0, 7),
('Cercasi passaggio per Bologna', 'Qualcuno va a Bologna lunedì mattina? Devo essere in stazione centrale per le 9. Offro benzina e buona compagnia! Partenza da Cesena.', '2025-12-07', 'Cerco passaggio per Bologna lunedì mattina...', '', 0, 5, 0, 9),
('Palestra universitaria - cerco compagno', 'Cerco qualcuno con cui andare in palestra al CUS. Vado di solito il martedì e giovedì pomeriggio. Livello intermedio, faccio principalmente pesi.', '2025-12-08', 'Cerco compagno per palestra al CUS...', '', 0, 9, 0, 10),
('Vendo Nintendo Switch + giochi', 'Vendo Nintendo Switch in ottime condizioni con: Mario Kart 8, Zelda BOTW, Animal Crossing. Tutto insieme 200€. La console ha ancora la garanzia.', '2025-12-09', 'Vendo Nintendo Switch con giochi a 200€...', '', 0, 14, 0, 9),
('Appunti Economia Aziendale', 'Ho tutti gli appunti del corso di Economia Aziendale del Prof. Bianchi, ben organizzati e con schemi riassuntivi. Li condivido gratis!', '2025-12-10', 'Condivido appunti Economia Aziendale...', '', 0, 34, 0, 4),

-- Post 11-20
('Tirocinio azienda software Cesena', 'La mia azienda cerca tirocinanti per sviluppo web (React + Node.js). 600€/mese di rimborso, possibilità di assunzione. Mandate CV!', '2025-12-11', 'Cerchiamo tirocinanti sviluppo web...', '', 0, 41, 0, 7),
('Chi ha preso la mia bici?', 'Oggi qualcuno ha preso la mia bici dal parcheggio di fronte alla mensa. È una Graziella verde acqua con cestino. Spero sia stato uno sbaglio!', '2025-12-12', 'Qualcuno ha preso la mia bici per sbaglio...', '', 0, 23, 0, 6),
('ATTENZIONE: Truffa affitti!', '⚠️ Volevo avvisare tutti di stare attenti a un annuncio per un appartamento in Via Romagna. Non versate soldi senza aver visto l''immobile di persona!', '2025-12-13', '⚠️ Attenzione a truffa sugli affitti...', '', 0, 89, 0, 3),
('Problema con segreteria studenti', 'Qualcun altro ha problemi con la segreteria? Ho mandato 3 email per un problema con il piano di studi e nessuno mi risponde da 2 settimane.', '2025-12-14', 'La segreteria non risponde alle email...', '', 0, 37, 0, 8),
('Confessione anonima', 'Mi sono innamorato/a di una persona che vedo sempre in biblioteca al terzo piano. Capelli ricci, occhiali e thermos blu. Se ti riconosci, sappi che illumini le mie giornate!', '2025-12-15', 'Confessione per persona in biblioteca...', '', 0, 67, 1, 8),
('Macchinetta caffè sempre rotta!', 'Ma è possibile che la macchinetta del caffè al piano terra di Ingegneria sia SEMPRE rotta? Ormai è un mese che non funziona!', '2025-12-16', 'La macchinetta del caffè non funziona da un mese...', '', 0, 56, 0, 5),
('Caricatore MacBook trovato', 'Ho trovato un caricatore MacBook in aula 3.2 di Informatica. Se è tuo contattami descrivendo il modello!', '2025-12-17', 'Trovato caricatore MacBook in aula 3.2...', '', 0, 7, 0, 3),
('Affitto monolocale da gennaio', 'Affitto monolocale arredato in centro Cesena, 5 min dalla stazione. 400€/mese + 50€ spese. Libero da gennaio. Solo studenti/esse.', '2025-12-18', 'Monolocale in affitto dal gennaio...', '', 0, 16, 0, 4),
('Recensione mensa: oggi TOP', 'Devo dire che oggi la mensa si è superata! Lasagne buonissime e il dolce era spettacolare. Ogni tanto fanno le cose per bene 👏', '2025-12-19', 'Oggi la mensa era ottima!', '', 0, 28, 0, 11),
('Capodanno: qualcuno organizza?', 'Qualcuno sa di feste o eventi per Capodanno a Cesena o dintorni? Siamo un gruppo di 5 persone e cerchiamo qualcosa di carino. Budget 30-40€.', '2025-12-20', 'Cerchiamo eventi per Capodanno...', '', 0, 19, 0, 11),

-- Post 21-30
('Buone feste a tutti!', 'Volevo augurare buone feste a tutta la community di Spotted Unibo Cesena! 🎄 Quest''anno è stato intenso ma ce l''abbiamo fatta. Ci rivediamo a gennaio!', '2025-12-21', 'Auguri di buone feste a tutti! 🎄', '', 0, 78, 0, 2),
('Confessione: il prof di Algoritmi', 'Devo confessare che il Prof. di Algoritmi mi terrorizza. Ogni volta che entra in aula il mio cuore inizia a battere fortissimo... per PAURA!', '2025-12-22', 'Il prof di Algoritmi mi fa paura...', '', 0, 103, 1, 10),
('Vendo scrivania IKEA', 'Vendo scrivania IKEA modello Micke bianca, usata solo 6 mesi. Perfette condizioni, 40€. Ritiro a mano zona Cesena centro.', '2025-12-23', 'Vendo scrivania IKEA 40€...', '', 0, 6, 0, 15),
('Cerco compagno per esame Fisica 2', 'Qualcuno deve dare Fisica 2 a gennaio? Mi piacerebbe studiare insieme, ho difficoltà con elettromagnetismo.', '2025-12-24', 'Cerco compagno studio per Fisica 2...', '', 0, 11, 0, 12),
('Sciarpa persa in mensa', 'Ho perso una sciarpa rossa con motivi floreali in mensa martedì scorso. Se qualcuno l''ha trovata per favore contattatemi!', '2025-12-25', 'Persa sciarpa rossa in mensa...', '', 0, 3, 0, 13),
('Consigli per tesi magistrale?', 'Sto cercando argomento per la tesi di magistrale in Informatica. Qualcuno ha suggerimenti su professori disponibili o temi interessanti?', '2025-12-26', 'Cerco suggerimenti per tesi magistrale...', '', 0, 24, 0, 7),
('Venddidivano letto', 'Vendo divano letto IKEA color grigio, ottime condizioni. 150€ trattabili. Misure: 200x90cm. Zona Cesena.', '2025-12-27', 'Divano letto IKEA 150€...', '', 0, 8, 0, 16),
('App per organizzare gruppo studio', 'Ragazzi, quale app usate per coordinarvi nei gruppi studio? Vorrei qualcosa con calendario condiviso.', '2025-12-28', 'Consigli app per gruppo studio?', '', 0, 14, 0, 6),
('Erasmus in Spagna - consigli?', 'Sto valutando l''Erasmus a Valencia per il prossimo anno. Qualcuno c''è già stato? Com''è l''università?', '2025-12-29', 'Consigli su Erasmus a Valencia...', '', 0, 19, 0, 14),
('PC portatile per ingegneria', 'Che caratteristiche dovrebbe avere un portatile per ingegneria informatica? Budget max 800€.', '2025-12-30', 'Consigli portatile per ingegneria...', '', 0, 21, 0, 12),

-- Gennaio 2026
('Buon anno a tutti!', 'Auguri di buon anno! Che il 2026 porti a tutti noi tanti successi negli esami! 🎆', '2026-01-01', 'Auguri di buon anno 2026! 🎆', '', 0, 52, 0, 2),
('Riprendono le lezioni - pronti?', 'Tra una settimana riprendono le lezioni. Qualcuno è riuscito a studiare durante le feste? Io zero 😅', '2026-01-02', 'Chi ha studiato durante le feste?', '', 0, 43, 0, 11),
('Vendo libro Sistemi Operativi', 'Vendo libro "Sistemi Operativi" di Silberschatz, ottava edizione. 30€, libro in ottime condizioni con alcuni appunti a matita.', '2026-01-03', 'Libro Sistemi Operativi 30€...', '', 0, 7, 0, 15),
('Qualcuno al cinema stasera?', 'C''è qualcuno che vuole andare al cinema stasera? Vorrei vedere il nuovo film Marvel al Multiplex.', '2026-01-03', 'Chi viene al cinema stasera?', '', 0, 9, 0, 13),
('Trovato portachiavi Pokemon', 'Trovato portachiavi di Pikachu in biblioteca al secondo piano. È sul banco dell''ufficio oggetti smarriti.', '2026-01-04', 'Trovato portachiavi Pokemon...', '', 0, 5, 0, 4),
('Cerco coinquilina zona via Romagna', 'Cerco coinquilina per appartamento in via Romagna. Stanza singola, 250€ al mese + spese. Appartamento con 2 altre ragazze.', '2026-01-04', 'Cerco coinquilina zona via Romagna...', '', 0, 13, 0, 14),
('Quando escono gli orari del secondo semestre?', 'Qualcuno sa quando pubblicano gli orari delle lezioni per il secondo semestre? Non li trovo da nessuna parte!', '2026-01-04', 'Dove trovo gli orari del secondo semestre?', '', 0, 31, 0, 8),
('Appunti Architetture degli Elaboratori', 'Qualcuno ha gli appunti completi di Architetture degli Elaboratori? I miei sono un disastro 😭', '2026-01-05', 'Cerco appunti Architetture Elaboratori...', '', 0, 18, 0, 12),
('Palestra: chi viene domani mattina?', 'Domani mattina alle 8 vado in palestra al CUS. C''è qualcuno che vuole unirsi? Faccio cardio e pesi.', '2026-01-05', 'Chi viene in palestra domani mattina?', '', 0, 6, 0, 10),
('Regalo cuccia per gatti', 'Regalo cuccia per gatti nuova, mai usata. L''avevo comprata ma il mio gatto la odia. Zona Cesena centro.', '2026-01-05', 'Regalo cuccia gatti nuova...', '', 0, 4, 0, 16),

-- Post 41-50
('Pranzo in mensa: cosa c''è oggi?', 'Qualcuno sa già cosa c''è in mensa oggi a pranzo? Voglio sapere se vale la pena o se mi faccio un panino!', '2026-01-06', 'Menu mensa di oggi?', '', 0, 22, 0, 11),
('Consigli su dove comprare materiale elettronica', 'Dove comprate componenti elettronici a Cesena? Servirebbero resistenze, LED e Arduino.', '2026-01-06', 'Negozi elettronica a Cesena?', '', 0, 15, 0, 15),
('Scambio: libro Python per libro Java', 'Ho il libro "Python per principianti" e mi serve un buon libro su Java. Qualcuno interessato a scambiare?', '2026-01-06', 'Scambio libro Python con Java...', '', 0, 8, 0, 13),
('Festa compleanno sabato sera', 'Sabato sera festeggio il compleanno a casa mia! Siete tutti invitati. Portate qualcosa da bere. Scrivetemi per l''indirizzo!', '2026-01-06', 'Festa compleanno sabato sera!', '', 0, 27, 0, 9),
('Problema con WiFi in facoltà', 'Solo a me non funziona il WiFi della facoltà oggi? Continua a disconnettersi ogni 5 minuti!', '2026-01-06', 'WiFi della facoltà non funziona...', '', 0, 38, 0, 5),
('Cercasi stagista per startup', 'Startup innovativa cerca stagista in ambito marketing digitale. Part-time, flessibilità oraria. Esperienza con social media.', '2026-01-06', 'Stage marketing per startup...', '', 0, 17, 0, 7),
('Sondaggio: quale mensa preferite?', 'Curiosità: quale mensa universitaria preferite e perché? Io voto per quella centrale!', '2026-01-06', 'Qual è la vostra mensa preferita?', '', 0, 29, 0, 14),
('Mouse wireless trovato', 'Trovato mouse wireless Logitech in aula 2.5. È all''ufficio oggetti smarriti.', '2026-01-07', 'Trovato mouse wireless...', '', 0, 3, 0, 16),
('Matematica Discreta: qualcuno la fa?', 'C''è qualcuno che sta seguendo Matematica Discreta? Vorrei confrontare gli esercizi svolti.', '2026-01-07', 'Chi segue Matematica Discreta?', '', 0, 12, 0, 12),
('Vendesi bicicletta da città', 'Vendo bici da città donna, colore nero, 21 rapporti. Ottime condizioni, con lucchetto incluso. 80€.', '2026-01-07', 'Bici da città 80€...', '', 0, 11, 0, 3),

-- Post 51-60
('Torneo di calcetto - iscrizioni aperte!', 'Si organizza torneo di calcetto tra studenti! Iscrizioni aperte fino a venerdì. 5€ quota di partecipazione. Scrivetemi!', '2026-01-07', 'Torneo di calcetto - iscrivetevi!', '', 0, 34, 0, 10),
('Drone trovato al campus', 'Qualcuno ha perso un drone al campus? Ne ho trovato uno vicino al parcheggio bici.', '2026-01-07', 'Trovato drone al campus...', '', 0, 8, 0, 4),
('Lezioni private di inglese', 'Offro lezioni private di inglese, livello C1 certificato. 12€/ora, disponibile anche online. Esperienza con preparazione esami.', '2026-01-07', 'Ripetizioni inglese 12€/ora...', '', 0, 16, 0, 14),
('Cerco programmatore per progetto', 'Cerco programmatore per progetto parallelo (app mobile). Competenze richieste: React Native. Collaborazione part-time.', '2026-01-07', 'Cerco programmatore React Native...', '', 0, 19, 0, 7),
('Giornata nevosa - campus bellissimo!', 'Che spettacolo il campus con la neve! Qualcuno vuole fare una battaglia di palle di neve dopo le lezioni? 😄', '2026-01-07', 'Neve al campus! Battaglia di palle? ⛄', '', 0, 41, 0, 13),
('Vendesi libri di diritto', 'Vendo libri di diritto: Diritto Privato, Diritto Costituzionale, Diritto Commerciale. 20€ ciascuno, ottimo stato.', '2026-01-07', 'Libri di diritto 20€ cad...', '', 0, 5, 0, 16),
('Consiglio ristoranti economici Cesena', 'Quali sono i migliori ristoranti economici a Cesena per studenti? Cerco posti dove si mangia bene spendendo poco.', '2026-01-07', 'Ristoranti economici a Cesena?', '', 0, 26, 0, 11),
('Lampadina fulminata aula studio', 'La lampadina dell''aula studio al terzo piano è fulminata da giorni. A chi bisogna segnalarlo?', '2026-01-07', 'Lampadina rotta aula studio...', '', 0, 17, 0, 5),
('Gruppo Telegram per appunti condivisi', 'Ho creato un gruppo Telegram per condividere appunti e materiale didattico. Chi vuole partecipare mi scriva!', '2026-01-07', 'Gruppo Telegram appunti condivisi...', '', 0, 48, 0, 6),
('Confessione: non capisco niente di Fisica', 'Confessione: sono a Ingegneria e non capisco NIENTE di Fisica 2. Come fanno gli altri a sembrare così tranquilli? 😭', '2026-01-07', 'Help: non capisco Fisica 2...', '', 0, 62, 1, 15);

-- ==================== POST_TAG ====================
INSERT INTO post_tag (post, tag) VALUES
-- Post 1-10
(1, 3), (2, 2), (2, 5), (3, 6), (4, 1), (5, 10), (6, 4), (7, 11), (8, 7), (9, 2),
-- Post 11-20
(10, 5), (11, 4), (12, 10), (13, 3), (13, 8), (14, 12), (15, 9), (16, 12), (17, 10), (18, 3),
-- Post 21-30
(19, 9), (20, 1), (21, 1), (22, 9), (23, 2), (24, 6), (25, 10), (26, 12), (27, 2), (28, 6),
-- Post 31-40
(29, 12), (30, 12), (31, 1), (32, 9), (33, 2), (33, 5), (34, 1), (35, 10), (36, 3), (37, 12),
-- Post 41-50
(38, 5), (39, 7), (40, 2), (41, 9), (42, 12), (43, 12), (44, 1), (45, 12), (46, 4), (47, 12),
-- Post 51-60
(48, 10), (49, 6), (50, 4), (51, 1), (52, 10), (53, 4), (54, 4), (55, 1), (56, 2), (56, 5), (57, 12), (58, 12), (59, 5), (60, 9), (60, 12);

-- ==================== USER_LIKES ====================
INSERT INTO user_likes (utente, post) VALUES
-- Likes variati per diversi post
(1, 1), (1, 4), (1, 10), (1, 21), (1, 31), (1, 51),
(2, 4), (2, 13), (2, 21), (2, 31), (2, 42), (2, 55),
(3, 4), (3, 11), (3, 15), (3, 22), (3, 34), (3, 44),
(4, 1), (4, 4), (4, 15), (4, 21), (4, 32), (4, 51),
(5, 4), (5, 13), (5, 21), (5, 30), (5, 45), (5, 60),
(6, 3), (6, 4), (6, 13), (6, 21), (6, 37), (6, 51),
(7, 4), (7, 11), (7, 15), (7, 22), (7, 32), (7, 55),
(8, 4), (8, 15), (8, 21), (8, 31), (8, 42), (8, 60),
(9, 1), (9, 4), (9, 15), (9, 22), (9, 34), (9, 51),
(10, 4), (10, 15), (10, 21), (10, 31), (10, 45), (10, 55),
(11, 4), (11, 13), (11, 19), (11, 21), (11, 32), (11, 41);

-- ==================== COMMENTI ====================
INSERT INTO commento (testocommento, datacommento, nomeautore, post, utente) VALUES
('Mi interessa! Posso contattarti?', '2025-12-01 14:30:00', 'Luca Martini', 1, 5),
('Il Bramanti è ancora disponibile?', '2025-12-02 11:20:00', 'Sofia Ricci', 2, NULL),
('Ci sto! Sono libero martedì e giovedì', '2025-12-03 18:00:00', 'Davide Pellegrini', 3, 9),
('Non vedo l''ora! 🎉', '2025-12-04 12:00:00', 'Sara Verdi', 4, 4),
('Prova a chiedere in portineria!', '2025-12-05 08:30:00', 'Elena Conti', 5, 6),
('Quanto chiedi per un pacchetto di 10 ore?', '2025-12-06 20:00:00', 'Giulia Romano', 6, NULL),
('Io parto da Forlì, passo da Cesena', '2025-12-07 18:00:00', 'Andrea Russo', 7, NULL),
('Ci sto! Ti scrivo in privato', '2025-12-08 20:00:00', 'Alessandro Ferrari', 8, 7),
('Accetti 180€?', '2025-12-09 16:00:00', 'Paolo Esposito', 9, NULL),
('Grazie mille! Ti ho scritto', '2025-12-10 14:00:00', 'Sofia Ricci', 10, NULL),
('Che tecnologie usate principalmente?', '2025-12-11 16:00:00', 'Davide Pellegrini', 11, 9),
('Hai provato a mettere un avviso in portineria?', '2025-12-12 16:30:00', 'Sara Verdi', 12, 4),
('Grazie mille per l''avviso!', '2025-12-13 09:30:00', 'Chiara Lombardi', 13, 10),
('Stessa situazione, è frustrante!', '2025-12-14 10:00:00', 'Alessandro Ferrari', 14, 7),
('OMG che romantico! 💕', '2025-12-15 13:00:00', 'Sara Verdi', 15, 4),
('FINALMENTE qualcuno che ne parla!', '2025-12-16 10:30:00', 'Marco Bianchi', 16, 3),
('Non è mio ma upvoto per visibilità!', '2025-12-17 10:00:00', 'Elena Conti', 17, 6),
('Posso vedere le foto?', '2025-12-18 11:00:00', 'Sofia Ricci', 18, NULL),
('Vero! Anche il primo era ottimo', '2025-12-19 18:00:00', 'Luca Martini', 19, 5),
('C''è una festa al Molo di Rimini', '2025-12-20 14:00:00', 'Davide Pellegrini', 20, 9),
('Buone feste anche a te! 🎄', '2025-12-21 18:00:00', 'Sara Verdi', 21, 4),
('HAHAHA io ho gli incubi!', '2025-12-22 11:00:00', 'Davide Pellegrini', 22, 9),
('Ancora disponibile?', '2025-12-23 09:00:00', 'Andrea Russo', 23, NULL),
('Anche io! Facciamo gruppo WhatsApp?', '2026-01-03 15:00:00', 'Elena Conti', 24, 6),
('Ho visto il cartello in mensa!', '2026-01-04 12:00:00', 'Marco Bianchi', 25, 3),
('Ottima idea! Mi aggiungi?', '2026-01-05 10:00:00', 'Chiara Lombardi', 59, 10);

-- ==================== SEGNALAZIONI ====================
INSERT INTO segnalazione (motivo, descrizione, datasegnalazione, stato, post, commento, utente_segnalante, utente_segnalato) VALUES
('Spam', 'Questo post sembra pubblicità mascherata', '2025-12-12 10:00:00', 'reviewed', 11, NULL, 5, 7),
('Informazioni false', 'La festa potrebbe essere una truffa', '2025-12-13 09:00:00', 'dismissed', 4, NULL, 8, 2),
('Contenuto inappropriato', 'La confessione è troppo esplicita', '2025-12-15 14:00:00', 'resolved', 15, NULL, 3, NULL);

-- ==================== MESSAGGI ====================
INSERT INTO messaggio (testomessaggio, datamessaggio, letto, mittente, destinatario) VALUES
('Ciao! Ho visto il tuo post sui libri. Ancora disponibile?', '2025-12-02 10:30:00', 1, 3, 4),
('Ciao Marco! Sì, ancora disponibile.', '2025-12-02 11:15:00', 1, 4, 3),
('Perfetto! Ci vediamo domani?', '2025-12-02 11:20:00', 1, 3, 4),
('Ciao! Info sulla festa del 20?', '2025-12-04 14:00:00', 1, 5, 2),
('Certo! Cosa vuoi sapere?', '2025-12-04 14:10:00', 1, 2, 5),
('Ci sono ancora biglietti?', '2025-12-04 14:12:00', 1, 5, 2),
('Sì, ci sono!', '2025-12-04 14:20:00', 1, 2, 5),
('Ciao! Gruppo studio Basi di Dati?', '2025-12-03 16:00:00', 1, 6, 7),
('Sì, mi interesserebbe!', '2025-12-03 16:30:00', 1, 7, 6),
('Hey, passaggio per Bologna ancora disponibile?', '2026-01-07 10:00:00', 0, 5, 9);
