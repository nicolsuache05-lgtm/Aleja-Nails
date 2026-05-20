<!-- ══════════════════════════════════════════════════════════
     CHATBOT ALEJA-NAILS — widget flotante
     Incluir antes de </body> en footer.php
════════════════════════════════════════════════════════════ -->

<style>
/* ── Botón flotante ── */
#chat-fab {
  position: fixed;
  bottom: 28px;
  right: 28px;
  width: 58px;
  height: 58px;
  border-radius: 50%;
  background: linear-gradient(135deg, #e8527a, #c93060);
  color: white;
  border: none;
  cursor: pointer;
  font-size: 26px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 20px rgba(200, 50, 90, .45);
  z-index: 9999;
  transition: transform .2s, box-shadow .2s;
}
#chat-fab:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 28px rgba(200, 50, 90, .55);
}

/* Pulso animado */
#chat-fab::after {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background: rgba(232, 82, 122, .35);
  animation: pulse-chat 2s infinite;
}
@keyframes pulse-chat {
  0%   { transform: scale(1);   opacity: .7; }
  70%  { transform: scale(1.5); opacity: 0;  }
  100% { transform: scale(1.5); opacity: 0;  }
}

/* Burbuja de notificación */
#chat-badge {
  position: absolute;
  top: -2px;
  right: -2px;
  width: 18px;
  height: 18px;
  background: #ff4444;
  border-radius: 50%;
  font-size: 10px;
  font-weight: 700;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
}

/* ── Ventana del chat ── */
#chat-window {
  position: fixed;
  bottom: 100px;
  right: 28px;
  width: 360px;
  max-height: 520px;
  background: white;
  border-radius: 20px;
  box-shadow: 0 8px 40px rgba(100, 20, 50, .18);
  display: none;
  flex-direction: column;
  z-index: 9998;
  overflow: hidden;
  font-family: 'Poppins', sans-serif;
  animation: slideUp .25s ease;
}
@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0);    }
}

/* Header del chat */
#chat-header {
  background: linear-gradient(135deg, #e8527a, #c93060);
  padding: 14px 18px;
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}
#chat-header .avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255,255,255,.25);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}
#chat-header .info .name {
  color: white;
  font-weight: 600;
  font-size: 14px;
}
#chat-header .info .status {
  color: rgba(255,255,255,.8);
  font-size: 11px;
  display: flex;
  align-items: center;
  gap: 4px;
}
#chat-header .info .status::before {
  content: '';
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #7fff7f;
  display: inline-block;
}
#chat-close {
  margin-left: auto;
  background: none;
  border: none;
  color: white;
  font-size: 20px;
  cursor: pointer;
  opacity: .8;
  line-height: 1;
  padding: 0;
}
#chat-close:hover { opacity: 1; }

/* Área de mensajes */
#chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: #fdf0f5;
  min-height: 0;
}
#chat-messages::-webkit-scrollbar { width: 4px; }
#chat-messages::-webkit-scrollbar-thumb { background: #f4c0d1; border-radius: 4px; }

/* Burbujas */
.msg {
  max-width: 82%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 13px;
  line-height: 1.5;
  word-break: break-word;
  white-space: pre-wrap;
}
.msg.bot {
  background: white;
  color: #4a2030;
  border-bottom-left-radius: 4px;
  box-shadow: 0 1px 4px rgba(0,0,0,.07);
  align-self: flex-start;
}
.msg.user {
  background: linear-gradient(135deg, #e8527a, #c93060);
  color: white;
  border-bottom-right-radius: 4px;
  align-self: flex-end;
}

/* Typing indicator */
.typing {
  display: flex;
  gap: 4px;
  align-items: center;
  padding: 10px 14px;
  background: white;
  border-radius: 16px;
  border-bottom-left-radius: 4px;
  width: fit-content;
  box-shadow: 0 1px 4px rgba(0,0,0,.07);
}
.typing span {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #e8527a;
  animation: bounce-dot .9s infinite;
}
.typing span:nth-child(2) { animation-delay: .15s; }
.typing span:nth-child(3) { animation-delay: .30s; }
@keyframes bounce-dot {
  0%, 60%, 100% { transform: translateY(0);    }
  30%            { transform: translateY(-6px); }
}

/* Sugerencias rápidas */
#chat-suggestions {
  padding: 8px 12px;
  display: flex;
  gap: 6px;
  flex-wrap: wrap;
  background: white;
  border-top: 1px solid #fce4ef;
  flex-shrink: 0;
}
.sug-btn {
  background: #fdf0f5;
  border: 1.5px solid #f4c0d1;
  color: #c0375a;
  border-radius: 20px;
  padding: 4px 12px;
  font-size: 11px;
  font-family: 'Poppins', sans-serif;
  cursor: pointer;
  transition: all .15s;
  white-space: nowrap;
}
.sug-btn:hover {
  background: #fce4ef;
  border-color: #e8527a;
}

/* Input */
#chat-input-area {
  display: flex;
  gap: 8px;
  padding: 12px 14px;
  background: white;
  border-top: 1px solid #fce4ef;
  flex-shrink: 0;
}
#chat-input {
  flex: 1;
  border: 1.5px solid #f4c0d1;
  border-radius: 24px;
  padding: 9px 16px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: #4a2030;
  background: #fdf0f5;
  outline: none;
  transition: border-color .2s;
}
#chat-input:focus { border-color: #e8527a; background: white; }
#chat-send {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #e8527a, #c93060);
  border: none;
  color: white;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: transform .15s;
}
#chat-send:hover { transform: scale(1.08); }
</style>

<!-- ── Botón flotante ── -->
<button id="chat-fab" onclick="toggleChat()" title="Chat con nosotros">
  💬
  <span id="chat-badge">1</span>
</button>

<!-- ── Ventana del chat ── -->
<div id="chat-window">

  <!-- Header -->
  <div id="chat-header">
    <div class="avatar">💅</div>
    <div class="info">
      <div class="name">Aleja-Nails Asistente</div>
      <div class="status">En línea ahora</div>
    </div>
    <button id="chat-close" onclick="toggleChat()" title="Cerrar">✕</button>
  </div>

  <!-- Mensajes -->
  <div id="chat-messages"></div>

  <!-- Sugerencias rápidas -->
  <div id="chat-suggestions">
    <button class="sug-btn" onclick="enviarSugerencia('Servicios y precios')">💅 Servicios</button>
    <button class="sug-btn" onclick="enviarSugerencia('Agendar cita')">📅 Agendar</button>
    <button class="sug-btn" onclick="enviarSugerencia('Horarios')">🕐 Horarios</button>
    <button class="sug-btn" onclick="enviarSugerencia('Métodos de pago')">💳 Pagos</button>
    <button class="sug-btn" onclick="enviarSugerencia('Teléfono y contacto')">📞 Contacto</button>
    <button class="sug-btn" onclick="enviarSugerencia('Dónde están ubicados')">📍 Ubicación</button>
  </div>

  <!-- Input -->
  <div id="chat-input-area">
    <input id="chat-input" type="text" placeholder="Escribe tu pregunta..."
           onkeydown="if(event.key==='Enter') enviarMensaje()">
    <button id="chat-send" onclick="enviarMensaje()" title="Enviar">➤</button>
  </div>

</div>

<script>
const CHAT_URL = '/Mi-proyecto-formativo/public/index.php?action=chatbot';
let chatAbierto = false;
let primerApertura = true;

function toggleChat() {
  chatAbierto = !chatAbierto;
  const win   = document.getElementById('chat-window');
  const badge = document.getElementById('chat-badge');
  const fab   = document.getElementById('chat-fab');

  win.style.display = chatAbierto ? 'flex' : 'none';
  badge.style.display = 'none';
  fab.textContent = chatAbierto ? '✕' : '💬';
  // Mantener el badge oculto en el botón
  fab.appendChild(badge);

  if (chatAbierto && primerApertura) {
    primerApertura = false;
    // Mensaje de bienvenida automático
    setTimeout(function() {
      agregarMensaje('bot',
        '¡Hola! 💅 Soy la asistente virtual de <b>Aleja-Nails</b>.\n\n'
        + '¿En qué te puedo ayudar hoy?\n\n'
        + 'Puedes usar los botones de abajo o escribir tu pregunta 😊'
      );
    }, 300);
  }

  if (chatAbierto) {
    setTimeout(function() {
      document.getElementById('chat-input').focus();
    }, 100);
  }
}

function agregarMensaje(tipo, texto) {
  const msgs = document.getElementById('chat-messages');
  const div  = document.createElement('div');
  div.className = 'msg ' + tipo;
  div.innerHTML = texto; // Permite HTML en respuestas del bot
  msgs.appendChild(div);
  msgs.scrollTop = msgs.scrollHeight;
}

function mostrarTyping() {
  const msgs = document.getElementById('chat-messages');
  const div  = document.createElement('div');
  div.className = 'typing';
  div.id = 'typing-indicator';
  div.innerHTML = '<span></span><span></span><span></span>';
  msgs.appendChild(div);
  msgs.scrollTop = msgs.scrollHeight;
}

function quitarTyping() {
  const t = document.getElementById('typing-indicator');
  if (t) t.remove();
}

function enviarMensaje() {
  const input = document.getElementById('chat-input');
  const texto = input.value.trim();
  if (!texto) return;

  agregarMensaje('user', texto);
  input.value = '';
  mostrarTyping();

  // Simular pequeño delay para naturalidad
  setTimeout(function() {
    fetch(CHAT_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ mensaje: texto })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
      quitarTyping();
      agregarMensaje('bot', data.respuesta || 'No pude procesar tu mensaje.');
    })
    .catch(function() {
      quitarTyping();
      agregarMensaje('bot', 'Hubo un error de conexión. Intenta de nuevo 😔');
    });
  }, 600 + Math.random() * 400);
}

function enviarSugerencia(texto) {
  document.getElementById('chat-input').value = texto;
  enviarMensaje();
}

// Mostrar badge después de 3 segundos si no han abierto el chat
setTimeout(function() {
  if (!chatAbierto) {
    const badge = document.getElementById('chat-badge');
    badge.style.display = 'flex';
  }
}, 3000);
</script>
