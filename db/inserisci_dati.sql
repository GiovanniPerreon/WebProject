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
('michele.s', 'password123', 'Michele Savi', 1, 1, 'default-avatar.png');

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

-- ==================== POST ====================
-- ==================== POST ====================
INSERT INTO post (titolopost, testopost, datapost, anteprimapost, imgpost, pinned, likes, anonimo, utente) VALUES
('Cercasi coinquilino zona campus', 
'Ciao a tutti! Cerco un coinquilino per un appartamento in zona campus, a 5 minuti a piedi dalla facoltà di Ingegneria. Stanza singola, 280€ al mese spese incluse. L''appartamento è già arredato e ha una bella cucina. Disponibile da febbraio. Contattatemi in privato!', 
'2025-12-15', 'Cerco un coinquilino per un appartamento in zona campus, a 5 minuti a piedi...', '', 0, 12, 0, 3),

('Vendo libri Analisi 1 e Fisica', 
'Vendo i seguenti libri in ottime condizioni: - Analisi Matematica 1 (Bramanti-Pagani-Salsa) 25€ - Fisica 1 (Mazzoldi) 20€ - Elementi di Algebra Lineare 15€. Prezzo trattabile se presi insieme. Posso consegnare in facoltà.', 
'2025-12-16', 'Vendo i seguenti libri in ottime condizioni: Analisi Matematica 1...', '', 0, 8, 0, 4),

('Qualcuno ha trovato un portafoglio?', 
'Ieri sera ho perso il portafoglio nella zona della biblioteca centrale, probabilmente tra le 18 e le 19. È di pelle marrone con le mie tessere universitarie dentro. Se qualcuno lo ha trovato mi contatti per favore, offro ricompensa!', 
'2025-12-17', 'Ieri sera ho perso il portafoglio nella zona della biblioteca centrale...', '', 0, 15, 0, 5),

('Gruppo studio per esame di Basi di Dati', 
'Ciao! Sto cercando persone per formare un gruppo studio per l''esame di Basi di Dati del Prof. Rossi. L''idea è di trovarci 2-3 volte a settimana in biblioteca per fare esercizi insieme. Chi è interessato?', 
'2025-12-17', 'Sto cercando persone per formare un gruppo studio per l''esame di Basi di Dati...', '', 0, 22, 0, 6),

('Festa di fine semestre - 20 Dicembre!', 
'🎉 FESTA DI FINE SEMESTRE 🎉 Venerdì 20 Dicembre al Circolo Universitario! Ingresso 5€ con consumazione inclusa. DJ set, karaoke e tanti premi! Non mancate! Info e prevendite in DM.', 
'2025-12-18', '🎉 FESTA DI FINE SEMESTRE! Venerdì 20 Dicembre al Circolo Universitario...', '', 0, 45, 0, 2),

('Offro ripetizioni di Programmazione', 
'Sono uno studente magistrale e offro ripetizioni di Programmazione (C, Java, Python) a studenti del primo e secondo anno. 15€/ora, possibilità di lezioni online o in presenza. Ho già esperienza come tutor.', 
'2025-12-18', 'Offro ripetizioni di Programmazione (C, Java, Python) a studenti del primo...', '', 0, 18, 0, 7),

('Confessione anonima', 
'Mi sono innamorato/a di una persona che vedo sempre in biblioteca al terzo piano. Capelli ricci, occhiali e sempre con un thermos blu. Se ti riconosci, sappi che il tuo sorriso mi illumina le giornate di studio!', 
'2025-12-19', 'Mi sono innamorato/a di una persona che vedo sempre in biblioteca...', '', 0, 67, 1, 8),

('Cercasi passaggio per Bologna lunedì', 
'Qualcuno va a Bologna lunedì mattina? Devo essere in stazione centrale per le 9. Offro benzina e buona compagnia! Partenza da Cesena.', 
'2025-12-19', 'Qualcuno va a Bologna lunedì mattina? Devo essere in stazione centrale...', '', 0, 5, 0, 9),

('Palestra universitaria - cerco compagno', 
'Ciao! Cerco qualcuno con cui andare in palestra al CUS. Vado di solito il martedì e giovedì pomeriggio. Livello intermedio, faccio principalmente pesi. Scrivetemi!', 
'2025-12-19', 'Cerco qualcuno con cui andare in palestra al CUS. Vado di solito il martedì...', '', 0, 9, 0, 10),

('ATTENZIONE: Truffa affitti!', 
'⚠️ Volevo avvisare tutti di stare attenti a un annuncio per un appartamento in Via Romagna. Ho quasi versato una caparra ma poi ho scoperto che l''appartamento non esiste! Non versate soldi senza aver visto l''immobile di persona!', 
'2025-12-20', '⚠️ Volevo avvisare tutti di stare attenti a un annuncio per un appartamento...', '', 0, 89, 0, 3),

('Appunti Economia Aziendale', 
'Ho tutti gli appunti del corso di Economia Aziendale del Prof. Bianchi, ben organizzati e con schemi riassuntivi. Li condivido gratis, scrivetemi in privato e vi mando il link al drive!', 
'2025-12-20', 'Ho tutti gli appunti del corso di Economia Aziendale del Prof. Bianchi...', '', 0, 34, 0, 4),

('Quando tolgono le macchine del caffè???', 
'Ma è possibile che la macchinetta del caffè al piano terra di Ingegneria sia SEMPRE rotta? Ormai è un mese che non funziona. Qualcuno sa se la sistemeranno mai?', 
'2025-12-21', 'Ma è possibile che la macchinetta del caffè al piano terra di Ingegneria...', '', 0, 56, 0, 5),

('Tirocinio azienda software Cesena', 
'La mia azienda cerca tirocinanti per sviluppo web (React + Node.js). 600€/mese di rimborso, possibilità di assunzione. Mandate CV a hr@techazienda.it citando questo post!', 
'2025-12-21', 'La mia azienda cerca tirocinanti per sviluppo web (React + Node.js)...', '', 0, 41, 0, 7),

('Chi ha preso la mia bici per sbaglio?', 
'Oggi pomeriggio qualcuno ha preso la mia bici dal parcheggio di fronte alla mensa. È una Graziella verde acqua con cestino. Spero sia stato uno sbaglio... se l''hai presa tu rimettila dove l''hai trovata please!', 
'2025-12-22', 'Oggi pomeriggio qualcuno ha preso la mia bici dal parcheggio di fronte...', '', 0, 23, 0, 6),

('Recensione mensa: oggi TOP', 
'Devo dire che oggi la mensa si è superata! Lasagne buonissime e il dolce era spettacolare. Ogni tanto fanno le cose per bene 👏', 
'2025-12-22', 'Devo dire che oggi la mensa si è superata! Lasagne buonissime e il dolce...', '', 0, 28, 0, 11),

('Problema con segreteria studenti', 
'Qualcun altro ha problemi con la segreteria? Ho mandato 3 email per un problema con il piano di studi e nessuno mi risponde da 2 settimane. Come avete fatto voi?', 
'2025-12-23', 'Qualcun altro ha problemi con la segreteria? Ho mandato 3 email per un...', '', 0, 37, 0, 8),

('Vendo Nintendo Switch + giochi', 
'Vendo Nintendo Switch in ottime condizioni con: Mario Kart 8, Zelda BOTW, Animal Crossing. Tutto insieme 200€. La console ha ancora la garanzia. Solo Cesena, no spedizioni.', 
'2025-12-23', 'Vendo Nintendo Switch in ottime condizioni con: Mario Kart 8, Zelda BOTW...', '', 0, 14, 0, 9),

('Confessione: il prof di Algoritmi', 
'Devo confessare che il Prof. di Algoritmi mi terrorizza. Ogni volta che entra in aula il mio cuore inizia a battere fortissimo... ma non per amore, per PAURA. Chi altri vive questa situazione?', 
'2025-12-24', 'Devo confessare che il Prof. di Algoritmi mi terrorizza. Ogni volta che entra...', '', 0, 103, 1, 10),

('Buone feste a tutti!', 
'Volevo augurare buone feste a tutta la community di Spotted Unibo Cesena! 🎄 Quest''anno è stato intenso ma ce l''abbiamo fatta. Ci rivediamo a gennaio più carichi che mai!', 
'2025-12-24', 'Volevo augurare buone feste a tutta la community di Spotted Unibo Cesena! 🎄...', '', 0, 78, 0, 2),

('Capodanno: qualcuno organizza?', 
'Ehi! Qualcuno sa di feste o eventi per Capodanno a Cesena o dintorni? Siamo un gruppo di 5 persone e cerchiamo qualcosa di carino. Budget sui 30-40€ a testa. Grazie!', 
'2025-12-25', 'Qualcuno sa di feste o eventi per Capodanno a Cesena o dintorni? Siamo un gruppo...', '', 0, 19, 0, 11),

('Caricatore MacBook trovato', 
'Ho trovato un caricatore MacBook in aula 3.2 di Informatica. Se è tuo contattami descrivendo il modello e ti dico dove ritirarlo!', 
'2025-12-26', 'Ho trovato un caricatore MacBook in aula 3.2 di Informatica. Se è tuo...', '', 0, 7, 0, 3),

('Affitto monolocale da gennaio', 
'Affitto monolocale arredato in centro Cesena, 5 min dalla stazione. 400€/mese + 50€ spese. Libero da gennaio. Solo studenti/esse, no fumatori. Per info e foto scrivetemi!', 
'2025-12-27', 'Affitto monolocale arredato in centro Cesena, 5 min dalla stazione. 400€/mese...', '', 0, 16, 0, 4),

('Cercasi insegnante di inglese', 'Sto cercando un insegnante di inglese per lezioni private a domicilio. Contattatemi se interessati!', 
'2025-12-18', 'Sto cercando un insegnante di inglese per lezioni private a domicilio...', 'default.jpg', 0, 5, 0, 1),

('Sciarpa persa', 'Ho perso una sciarpa rossa con motivi floreali. Se qualcuno la trovasse, per favore contattatemi.', 
'2025-12-18', 'Ho perso una sciarpa rossa con motivi floreali. Se qualcuno la trovasse...', 'frog.jpg', 0, 3, 0, 2);

INSERT INTO user_likes (utente, post) VALUES
(1, 1),
(2, 1),
(1, 2);

-- ==================== POST_TAG ====================
INSERT INTO post_tag (post, tag) VALUES
(1, 3),
(2, 2),
(2, 5),
(3, 10),
(4, 6),
(5, 1),
(6, 4),
(7, 9),
(8, 11),
(9, 7),
(10, 3),
(10, 8),
(11, 5),
(12, 12),
(12, 9),
(13, 4),
(14, 10),
(15, 9),
(16, 12),
(17, 2),
(18, 9),
(19, 1),
(20, 1),
(22, 3),
(1, 4);

-- ==================== COMMENTI ====================
-- utente: ID dell'utente registrato (NULL se non registrato)
INSERT INTO commento (testocommento, datacommento, nomeautore, post, utente) VALUES

('Ciao! Mi interessa molto, sono uno studente di Economia. Posso contattarti?', '2025-12-15 14:30:00', 'Luca Martini', 1, 5),
('Accetti anche ragazze? Sono al secondo anno di Scienze Politiche', '2025-12-15 16:45:00', 'Anna Greco', 1, NULL),
('Il prezzo è trattabile?', '2025-12-16 09:15:00', 'Roberto Neri', 1, NULL),

('Il Bramanti è ancora disponibile?', '2025-12-16 11:20:00', 'Sofia Ricci', 2, NULL),
('Ti scrivo in privato per il Mazzoldi!', '2025-12-16 15:00:00', 'Giovanni Ferri', 2, NULL),

('Prova a chiedere al servizio oggetti smarriti in portineria!', '2025-12-17 08:30:00', 'Elena Conti', 3, 6),
('In bocca al lupo, spero tu lo ritrovi!', '2025-12-17 10:00:00', 'Francesca Galli', 3, 8),
('L''ho visto! Qualcuno l''ha portato in segreteria studenti', '2025-12-17 14:25:00', 'Marco Bianchi', 3, 3),

('Ci sto! Sono libero martedì e giovedì', '2025-12-17 18:00:00', 'Davide Pellegrini', 4, 9),
('Anch''io! Facciamo un gruppo WhatsApp?', '2025-12-17 18:30:00', 'Chiara Lombardi', 4, 10),
('Perfetto, vi scrivo in privato con il link del gruppo!', '2025-12-17 19:00:00', 'Elena Conti', 4, 6),
('Posso unirmi anche se sono un po'' indietro col programma?', '2025-12-18 09:00:00', 'Matteo Santini', 4, 11),

('Non vedo l''ora! 🎉', '2025-12-18 12:00:00', 'Sara Verdi', 5, 4),
('Prevendite ancora disponibili?', '2025-12-18 14:30:00', 'Luca Martini', 5, 5),
('L''anno scorso è stata pazzesca, quest''anno ci sarò sicuro!', '2025-12-18 16:00:00', 'Alessandro Ferrari', 5, 7),
('C''è limite di età?', '2025-12-19 10:00:00', 'Marco Bianchi', 5, 3),

('Quanto chiedi per un pacchetto di 10 ore?', '2025-12-18 20:00:00', 'Giulia Romano', 6, NULL),
('Fai anche ripetizioni di C++?', '2025-12-19 11:00:00', 'Paolo Esposito', 6, NULL),

('OMG che romantico! 💕', '2025-12-19 13:00:00', 'Sara Verdi', 7, 4),
('Fatti avanti! Che hai da perdere?', '2025-12-19 14:30:00', 'Francesca Galli', 7, 8),
('Plot twist: sono io! (scherzo)', '2025-12-19 16:00:00', 'Marco Bianchi', 7, 3),
('Bellissimo, in bocca al lupo!', '2025-12-19 17:30:00', 'Elena Conti', 7, 6),

('Io parto da Forlì, se ti va bene passerei da Cesena verso le 7:30', '2025-12-19 18:00:00', 'Andrea Russo', 8, NULL),

('Ci sto! Ti scrivo in privato', '2025-12-19 20:00:00', 'Alessandro Ferrari', 9, 7),
('Anche io vado quei giorni, aggiungetemi!', '2025-12-20 08:00:00', 'Davide Pellegrini', 9, 9),

('Grazie mille per l''avviso!', '2025-12-20 09:30:00', 'Chiara Lombardi', 10, 10),
('È successo anche a un mio amico l''anno scorso. Fate sempre attenzione!', '2025-12-20 10:00:00', 'Luca Martini', 10, 5),
('Hai fatto denuncia?', '2025-12-20 11:00:00', 'Giulia Rossi', 10, 2),
('Condivido per visibilità!', '2025-12-20 12:30:00', 'Matteo Santini', 10, 11),

('Grazie mille! Ti ho scritto in privato', '2025-12-20 14:00:00', 'Sofia Ricci', 11, NULL),
('Sei un angelo! 🙏', '2025-12-20 15:30:00', 'Roberto Neri', 11, NULL),
('Funzionano anche per il corso del Prof. Verdi?', '2025-12-21 09:00:00', 'Anna Greco', 11, NULL),

('FINALMENTE qualcuno che ne parla!', '2025-12-21 10:30:00', 'Marco Bianchi', 12, 3),
('Quella al primo piano funziona ma fa il caffè che sa di plastica', '2025-12-21 11:00:00', 'Alessandro Ferrari', 12, 7),
('Ho segnalato più volte ma niente...', '2025-12-21 14:00:00', 'Elena Conti', 12, 6),

('Che tecnologie usate principalmente?', '2025-12-21 16:00:00', 'Davide Pellegrini', 13, 9),
('È richiesta esperienza pregressa?', '2025-12-22 09:00:00', 'Matteo Santini', 13, 11),
('Ho mandato il CV, grazie per l''opportunità!', '2025-12-22 11:00:00', 'Chiara Lombardi', 13, 10),

('Controlla se per caso è finita nel deposito bici dell''università', '2025-12-22 15:00:00', 'Giulia Rossi', 14, 2),
('Hai provato a mettere un avviso in portineria?', '2025-12-22 16:30:00', 'Sara Verdi', 14, 4),

('Vero! Anche il primo era ottimo', '2025-12-22 18:00:00', 'Luca Martini', 15, 5),
('Dove sono queste lasagne quando ci vado io? 😭', '2025-12-22 19:00:00', 'Francesca Galli', 15, 8),

('Stessa situazione, è frustrante!', '2025-12-23 10:00:00', 'Alessandro Ferrari', 16, 7),
('Io sono andato di persona, dopo 2 ore di fila hanno risolto', '2025-12-23 11:30:00', 'Marco Bianchi', 16, 3),
('Prova a chiamare, a volte rispondono al telefono', '2025-12-23 14:00:00', 'Giulia Rossi', 16, 2),

('Accetti 180€?', '2025-12-23 16:00:00', 'Paolo Esposito', 17, NULL),
('Ancora disponibile?', '2025-12-24 09:00:00', 'Andrea Russo', 17, NULL),

('HAHAHA io ho gli incubi prima di ogni suo esame', '2025-12-24 11:00:00', 'Davide Pellegrini', 18, 9),
('Il suo sguardo quando sbagli una risposta... 💀', '2025-12-24 12:30:00', 'Chiara Lombardi', 18, 10),
('Ma poi è bravissimo, solo che fa paura!', '2025-12-24 14:00:00', 'Elena Conti', 18, 6),
('Trauma collettivo di Informatica 😂', '2025-12-24 15:30:00', 'Matteo Santini', 18, 11),
('Aspetta di vedere la sua faccia quando consegni il compito in ritardo...', '2025-12-24 17:00:00', 'Marco Bianchi', 18, 3),

('Buone feste anche a te! 🎄', '2025-12-24 18:00:00', 'Sara Verdi', 19, 4),
('Grazie! Buon Natale a tutti! 🎅', '2025-12-24 19:00:00', 'Luca Martini', 19, 5),
('Il prossimo semestre sarà ancora peggio 😅', '2025-12-24 20:00:00', 'Alessandro Ferrari', 19, 7),

('Noi facciamo una cosa in casa, se volete unirvi!', '2025-12-25 12:00:00', 'Francesca Galli', 20, 8),
('C''è una festa al Molo di Rimini, biglietti a 35€', '2025-12-25 14:00:00', 'Davide Pellegrini', 20, 9),

('Non è mio ma upvoto per visibilità!', '2025-12-26 10:00:00', 'Elena Conti', 21, 6),

('Posso vedere le foto? Ti scrivo in privato', '2025-12-27 11:00:00', 'Sofia Ricci', 22, NULL),
('Animali ammessi?', '2025-12-27 14:00:00', 'Roberto Neri', 22, NULL),

('Ciao, io sono disponibile per lezioni di inglese!', '2025-12-19 10:30:00', 'Pinco Pallino', 1, NULL),
('Che livello cerchi?', '2025-12-19 14:15:00', 'Giovanni Rossi', 1, NULL),
('Ottimo lavoro! La segreteria conferma.', '2025-12-20 09:00:00', 'Admin Sistema', 2, 1),
('Di che marca era?', '2025-12-20 11:45:00', 'Laura Bianchi', 2, NULL);

-- ==================== USER_LIKES ====================
INSERT INTO user_likes (utente, post) VALUES
(1, 5), (1, 10), (1, 19),
(2, 4), (2, 7), (2, 10), (2, 18), (2, 19),
(3, 2), (3, 5), (3, 7), (3, 10), (3, 12), (3, 18),
(4, 1), (4, 5), (4, 6), (4, 7), (4, 10), (4, 11), (4, 18), (4, 19),
(5, 3), (5, 5), (5, 7), (5, 10), (5, 12), (5, 15), (5, 18),
(6, 4), (6, 5), (6, 7), (6, 10), (6, 13), (6, 18), (6, 19),
(7, 5), (7, 7), (7, 9), (7, 10), (7, 12), (7, 18),
(8, 5), (8, 7), (8, 10), (8, 15), (8, 18), (8, 19), (8, 20),
(9, 1), (9, 5), (9, 7), (9, 10), (9, 17), (9, 18),
(10, 4), (10, 5), (10, 7), (10, 10), (10, 18), (10, 19),
(11, 5), (11, 7), (11, 10), (11, 12), (11, 15), (11, 18);

-- ==================== SEGNALAZIONI ====================
INSERT INTO segnalazione (motivo, descrizione, datasegnalazione, stato, post, commento, utente_segnalante, utente_segnalato) VALUES
('Contenuto offensivo', 'Il commento contiene insulti verso altri studenti', '2025-12-20 15:30:00', 'pending', NULL, 5, 4, NULL),
('Spam', 'Questo post sembra pubblicità mascherata', '2025-12-21 10:00:00', 'reviewed', 6, NULL, 5, 7),
('Informazioni false', 'La festa pubblicizzata potrebbe essere una truffa, non trovo conferme', '2025-12-22 09:00:00', 'dismissed', 5, NULL, 8, 2),
('Contenuto inappropriato', 'La confessione anonima è un po'' troppo esplicita', '2025-12-23 14:00:00', 'resolved', 7, NULL, 3, NULL),
('Altro', 'Questo utente sta spammando lo stesso annuncio più volte', '2025-12-24 11:00:00', 'pending', 22, NULL, 6, 4),
('Violazione copyright', 'Gli appunti condivisi potrebbero violare il copyright del professore', '2025-12-25 16:00:00', 'pending', 11, NULL, 9, 4);

-- ==================== MESSAGGI ====================
-- Conversazione tra Marco Bianchi (3) e Sara Verdi (4)
INSERT INTO messaggio (testomessaggio, datamessaggio, letto, mittente, destinatario) VALUES
('Ciao! Ho visto il tuo post sui libri. Sono interessato al libro di Analisi 1. È ancora disponibile?', '2025-12-16 10:30:00', 1, 3, 4),
('Ciao Marco! Sì, è ancora disponibile. Quando ti va di vederlo?', '2025-12-16 11:15:00', 1, 4, 3),
('Perfetto! Domani pomeriggio in università verso le 15? Ci vediamo in biblioteca?', '2025-12-16 11:20:00', 1, 3, 4),
('Va benissimo! Ci vediamo domani alle 15 all''ingresso della biblioteca centrale 👍', '2025-12-16 11:25:00', 1, 4, 3),
('Ottimo, a domani!', '2025-12-16 11:30:00', 1, 3, 4),

-- Conversazione tra Giulia Rossi (2) e Luca Martini (5)
('Ciao Giulia! Ho visto che sei amministratrice. Posso chiederti info sulla festa del 20 dicembre?', '2025-12-18 14:00:00', 1, 5, 2),
('Certo! Cosa vuoi sapere?', '2025-12-18 14:10:00', 1, 2, 5),
('È confermata al Circolo Universitario? E ci sono ancora biglietti disponibili?', '2025-12-18 14:12:00', 1, 5, 2),
('Sì confermata! E sì, ci sono ancora molti biglietti. Puoi comprarli in DM o al circolo fino a giovedì.', '2025-12-18 14:20:00', 1, 2, 5),
('Perfetto, grazie mille!', '2025-12-18 14:22:00', 1, 5, 2),

-- Conversazione tra Elena Conti (6) e Alessandro Ferrari (7)
('Ciao! Hai visto il mio post sul gruppo studio per Basi di Dati? Ti va di unirti?', '2025-12-17 16:00:00', 1, 6, 7),
('Ciao Elena! Sì, mi interesserebbe molto. Quando pensavi di iniziare?', '2025-12-17 16:30:00', 1, 7, 6),
('Da lunedì prossimo, lunedì e giovedì dalle 14 alle 17. Ti va bene?', '2025-12-17 16:45:00', 1, 6, 7),
('Perfetto! Lunedì non posso ma giovedì ci sono sicuramente.', '2025-12-17 17:00:00', 1, 7, 6),
('Va bene! Ti aggiungo al gruppo WhatsApp dove ci coordiniamo. Ti mando il link.', '2025-12-17 17:05:00', 1, 6, 7),

-- Conversazione tra Francesca Galli (8) e Davide Pellegrini (9)
('Ciao! Ho visto che cerchi un passaggio per Bologna. Io parto lunedì alle 8:30, troppo presto?', '2025-12-19 18:00:00', 1, 9, 8),
('Ciao Davide! Le 8:30 vanno benissimo! Grazie mille, mi salvi la vita!', '2025-12-19 18:15:00', 1, 8, 9),
('Nessun problema! Dove posso passarti a prendere?', '2025-12-19 18:20:00', 1, 9, 8),
('Abito in Via Saffi, vicino al parcheggio della stazione. Ti va bene?', '2025-12-19 18:25:00', 1, 8, 9),
('Perfetto, conosco. Ci vediamo lunedì alle 8:30 al parcheggio! Ti mando un messaggio quando arrivo.', '2025-12-19 18:30:00', 1, 9, 8),
('Grazie ancora! A lunedì!', '2025-12-19 18:32:00', 1, 8, 9),

-- Conversazione tra Chiara Lombardi (10) e Matteo Santini (11)
('Ciao! Anche tu vai in palestra al CUS? Che giorni ci vai?', '2025-12-19 20:00:00', 1, 11, 10),
('Ciao Matteo! Sì vado spesso. Di solito lunedì, mercoledì e venerdì. Tu?', '2025-12-19 20:15:00', 1, 10, 11),
('Io martedì e giovedì ma potrei cambiare. Magari ci vediamo mercoledì?', '2025-12-19 20:20:00', 1, 11, 10),
('Sì dai! Mercoledì vado verso le 15:30. Ti va bene quell''orario?', '2025-12-19 20:25:00', 1, 10, 11),
('Perfetto! Ci vediamo mercoledì in palestra allora 💪', '2025-12-19 20:30:00', 1, 11, 10),

-- Messaggi non letti recenti
('Ciao! Sono interessato al tuo Nintendo Switch. È ancora disponibile?', '2025-12-27 10:00:00', 0, 5, 9),
('Hey, ti va di studiare insieme per l''esame di Algoritmi?', '2025-12-27 11:30:00', 0, 7, 6),
('Grazie per gli appunti! Sono stati utilissimi! 🙏', '2025-12-27 12:00:00', 0, 8, 4),
('Ciao, hai per caso trovato delle chiavi in biblioteca ieri?', '2025-12-27 13:15:00', 0, 3, 5),
('Sei riuscito a risolvere il problema con la segreteria? Io ho lo stesso problema!', '2025-12-27 14:00:00', 0, 10, 8);
