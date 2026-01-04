<h2>Messaggi</h2>

<div class="messages-container">
        <!-- Conversations List -->
        <div class="conversations-list">
            <h3>Conversazioni</h3>
            <?php if(empty($templateParams["conversations"])): ?>
                <p class="empty-message">Nessuna conversazione ancora. Inizia a chattare con altri utenti!</p>
            <?php else: ?>
                <?php foreach($templateParams["conversations"] as $conv): ?>
                    <a href="messaggi.php?user=<?php echo $conv['idutente']; ?>" 
                       class="conversation-item <?php echo isset($templateParams["selectedUser"]) && $templateParams["selectedUser"]['idutente'] == $conv['idutente'] ? 'active' : ''; ?>">
                        <img src="<?php echo UPLOAD_DIR.$conv['imgprofilo']; ?>" alt="<?php echo $conv['nome']; ?>" class="conversation-avatar">
                        <div class="conversation-info">
                            <div class="conversation-header">
                                <strong class="conversation-name"><?php echo $conv['nome']; ?></strong>
                                <small class="message-time"><?php echo date('d/m H:i', strtotime($conv['data_ultimo_messaggio'])); ?></small>
                            </div>
                            <div class="conversation-footer">
                                <p class="last-message"><?php echo strlen($conv['ultimo_messaggio']) > 50 ? substr($conv['ultimo_messaggio'], 0, 50).'...' : $conv['ultimo_messaggio']; ?></p>
                                <?php if($conv['non_letti'] > 0): ?>
                                    <span class="unread-badge"><?php echo $conv['non_letti']; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Chat Area -->
        <div class="chat-area">
            <?php if(isset($templateParams["selectedUser"])): ?>
                <!-- Chat Header -->
                <div class="chat-header">
                    <img src="<?php echo UPLOAD_DIR.$templateParams["selectedUser"]['imgprofilo']; ?>" 
                         alt="<?php echo $templateParams["selectedUser"]['nome']; ?>" 
                         class="chat-avatar">
                    <div>
                        <strong><?php echo $templateParams["selectedUser"]['nome']; ?></strong>
                        <small>@<?php echo $templateParams["selectedUser"]['username']; ?></small>
                    </div>
                </div>

                <!-- Messages -->
                <div class="messages-box" id="messages-box">
                    <?php foreach($templateParams["messages"] as $msg): ?>
                        <div class="message <?php echo $msg['mittente'] == $_SESSION['idutente'] ? 'sent' : 'received'; ?>">
                            <div class="message-content">
                                <p><?php echo nl2br(htmlspecialchars($msg['testomessaggio'])); ?></p>
                                <small class="message-timestamp"><?php echo date('H:i', strtotime($msg['datamessaggio'])); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Message Input -->
                <form class="message-form" id="message-form" action="api-messages.php" method="POST">
                    <input type="hidden" name="action" value="sendMessage">
                    <input type="hidden" name="destinatario" value="<?php echo $templateParams["selectedUser"]['idutente']; ?>">
                    <textarea name="messaggio" placeholder="Scrivi un messaggio..." required maxlength="1000"></textarea>
                    <button type="submit">Invia</button>
                </form>
            <?php else: ?>
                <div class="empty-chat">
                    <p>Seleziona una conversazione o iniziane una nuova!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

<script>
// Scroll automatico in fondo alla chat
const messagesBox = document.getElementById('messages-box');
if (messagesBox) {
    messagesBox.scrollTop = messagesBox.scrollHeight;
}
</script>

