<!DOCTYPE html>
<html lang="it">
<head>
    <title><?php echo $templateParams["titolo"]; ?></title>
    <link rel="stylesheet" href="./css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php
    if(isset($templateParams["js"])):
        foreach($templateParams["js"] as $script):
    ?>
        <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</head>
<body>
    <header>
        <h1>Spotted Unibo Cesena</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="contatti.php">Contatti</a></li>
            <?php if(isUserLoggedIn()): ?>
            <li><a href="profilo.php">Profilo</a></li>
            <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <main>
    <?php
    if(isset($templateParams["nome"])){
        require("template/".$templateParams["nome"]);
    }
    ?>
    </main>
    <aside>
        <section>
            <h2>Bacheca</h2>
            <ul>
                <?php 
                $tags = $dbh->getTags();
                foreach($tags as $tag): 
                ?>
                <li><a href="tag.php?id=<?php echo $tag["idtag"]; ?>"><?php echo $tag["nometag"]; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </section>
    </aside>
    <footer>
        <p>Spotted Unibo Cesena</p>
    </footer>

    <!-- Modal Segnalazione -->
    <div id="segnalaModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h3>⚠️ Segnala contenuto</h3>
            <form id="segnalaForm">
                <input type="hidden" id="segnala_idpost" name="idpost" value="" />
                <input type="hidden" id="segnala_idcommento" name="idcommento" value="" />
                
                <label for="segnala_motivo">Motivo della segnalazione:</label>
                <select id="segnala_motivo" name="motivo" required>
                    <option value="">Seleziona un motivo...</option>
                    <option value="Contenuto offensivo">Contenuto offensivo</option>
                    <option value="Spam">Spam</option>
                    <option value="Contenuto inappropriato">Contenuto inappropriato</option>
                    <option value="Violazione copyright">Violazione copyright</option>
                    <option value="Informazioni false">Informazioni false</option>
                    <option value="Altro">Altro</option>
                </select>
                
                <label for="segnala_descrizione">Descrizione (opzionale):</label>
                <textarea id="segnala_descrizione" name="descrizione" placeholder="Aggiungi dettagli sulla segnalazione..."></textarea>
                
                <button type="submit" class="btn btn-primary">Invia Segnalazione</button>
            </form>
        </div>
    </div>

    <script>
    // Segnalazione modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('segnalaModal');
        const closeBtn = document.querySelector('.modal-close');
        
        // Open modal on segnala button click
        document.querySelectorAll('.segnala-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const idpost = this.getAttribute('data-idpost') || '';
                const idcommento = this.getAttribute('data-idcommento') || '';
                document.getElementById('segnala_idpost').value = idpost;
                document.getElementById('segnala_idcommento').value = idcommento;
                modal.style.display = 'flex';
            });
        });
        
        // Close modal
        if(closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        }
        
        // Close on outside click
        window.addEventListener('click', function(e) {
            if(e.target === modal) {
                modal.style.display = 'none';
            }
        });
        
        // Submit segnalazione form
        const segnalaForm = document.getElementById('segnalaForm');
        if(segnalaForm) {
            segnalaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('processa-segnalazione.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.success) {
                        modal.style.display = 'none';
                        segnalaForm.reset();
                    }
                })
                .catch(err => alert('Errore nell\'invio della segnalazione'));
            });
        }
    });
    </script>

    <!-- Toast Notifications & Post Actions -->
    <script src="js/notifications.js"></script>
    <script src="js/post-actions.js"></script>
</body>
</html>
