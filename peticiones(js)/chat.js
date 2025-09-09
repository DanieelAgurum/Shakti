document.addEventListener("DOMContentLoaded", () => {
    const chatList = document.getElementById("chat-list");
    const chatMensajes = document.querySelector(".chat-mensajes");
    const idUsuario = Number(document.getElementById("id_usuaria").value);

    const formulario = document.getElementById("formulario");
    const mensajeInput = document.getElementById("mensaje");
    const archivoInput = document.getElementById("archivo");

    let canalPusher = null;
    let idAmigoActual = null;

    // Inicializar Pusher
    const pusher = new Pusher('0469c2ac8ae0b818938a', {
        cluster: 'us2',
        encrypted: true
    });

    // Función para verificar si chat ya está en la lista
    function existeChatEnLista(idAmigo) {
        return !!document.querySelector(`.chat-activo[data-id-amigo="${idAmigo}"]`);
    }

    // Función para agregar nuevo chat a la lista
    function agregarChatNuevo(usuario) {
        const div = document.createElement("div");
        div.className = "chat-activo";
        div.dataset.idAmigo = usuario.id;

        div.innerHTML = `
            <img src="${usuario.foto || ''}" alt="${usuario.nickname}">
            <div class="info-chat">
                <strong>${usuario.nickname}</strong>
                <small></small>
            </div>
        `;

        div.addEventListener("click", () => {
            document.querySelectorAll(".chat-activo").forEach(el => el.classList.remove("activo"));
            div.classList.add("activo");

            seleccionarChat(usuario.id);
            cargarMensajes(idUsuario, usuario.id);
            suscribirseAlChat(usuario.id);
        });

        chatList.prepend(div);
    }

    function suscribirseAlChat(idAmigo) {
        if (canalPusher) {
            pusher.unsubscribe(canalPusher.name);
        }
        idAmigoActual = Number(idAmigo);
        const canalNombre = 'chat-' + Math.min(idUsuario, idAmigoActual) + '-' + Math.max(idUsuario, idAmigoActual);
        canalPusher = pusher.subscribe(canalNombre);

        canalPusher.bind('nuevo-mensaje', async data => {
            const idEmisor = Number(data.id_emisor);
            const idReceptor = Number(data.id_receptor);

            const esParaChatActivo =
                (idEmisor === idAmigoActual && idReceptor === idUsuario) ||
                (idEmisor === idUsuario && idReceptor === idAmigoActual);

            // Evitar duplicar mensajes que yo mismo envié
            if (idEmisor === idUsuario) {
                return; // Si yo soy el emisor, no lo agrego porque ya lo hice en el submit
            }

            const actualizarUltimoMensaje = (idAmigo, texto) => {
                let chatDiv = document.querySelector(`.chat-activo[data-id-amigo="${idAmigo}"]`);
                if (chatDiv) {
                    const ultimoSmall = chatDiv.querySelector('small');
                    if (ultimoSmall) {
                        ultimoSmall.textContent = texto;
                    }
                    chatDiv.remove();
                    chatList.prepend(chatDiv);
                }
            };

            if (esParaChatActivo) {
                fetch(`/chat/obtener_mensaje.php?id=${data.id_mensaje}`)
                    .then(res => res.json())
                    .then(msgCompleto => {
                        if (!msgCompleto.error) {
                            agregarMensajeAlChat({
                                id_mensaje: msgCompleto.id_mensaje ?? null,
                                id_emisor: msgCompleto.id_emisor ?? null,
                                id_receptor: msgCompleto.id_receptor ?? null,
                                mensaje: msgCompleto.mensaje ?? "",
                                tipo: msgCompleto.tipo ?? (msgCompleto.contenido ? 'imagen' : 'texto'),
                                contenido: msgCompleto.contenido ?? null,
                                es_mensaje_yo: msgCompleto.id_emisor === idUsuario,
                                creado_en: msgCompleto.creado_en,
                            });

                            const textoUltimo = (msgCompleto.mensaje && msgCompleto.mensaje.trim() !== "")
                                ? msgCompleto.mensaje
                                : "[Archivo o imagen]";
                            const idAmigo = (msgCompleto.id_emisor === idUsuario) ? msgCompleto.id_receptor : msgCompleto.id_emisor;
                            actualizarUltimoMensaje(idAmigo, textoUltimo);
                        }
                    });
                return;
            }

            // Si es para mí y no es el chat activo
            if (idReceptor === idUsuario) {
                let chatDiv = document.querySelector(`.chat-activo[data-id-amigo="${idEmisor}"]`);
                const textoUltimo = (data.mensaje && data.mensaje.trim() !== "")
                    ? data.mensaje
                    : "[Archivo o imagen]";
                if (chatDiv) {
                    actualizarUltimoMensaje(idEmisor, textoUltimo);
                } else {
                    try {
                        const res = await fetch(`/chat/obtener_usuario.php?id=${idEmisor}`);
                        const usuario = await res.json();
                        if (!usuario.error) {
                            usuario.ultimo_mensaje = textoUltimo;
                            agregarChatNuevo(usuario);
                        }
                    } catch (error) {
                        console.error("Error al obtener datos del usuario:", error);
                    }
                }
            }
        });
    }

    function cargarChats() {
        fetch("/chat/cargar_chats.php")
            .then(res => {
                if (!res.ok) throw new Error("Error en la respuesta del servidor");
                return res.json();
            })
            .then(data => {
                chatList.innerHTML = "";
                data.forEach(chat => {
                    // console.log(chat);
                    const div = document.createElement("div");
                    div.className = "chat-activo";
                    div.dataset.idAmigo = chat.id_amigo;

                    div.innerHTML = `
                        <img src="${chat.foto || ''}" alt="${chat.nickname}">
                        <div class="info-chat">
                            <strong>${chat.nickname}</strong>
                            <small id="ultimo-${chat.id_amigo}">${chat.ultimo_mensaje} ${chat.id_amigo}</small>
                        </div>
                    `;
                    div.addEventListener("click", () => {
                        document.querySelectorAll(".chat-activo").forEach(el => el.classList.remove("activo"));
                        div.classList.add("activo");

                        seleccionarChat(chat.id_amigo);
                        cargarMensajes(idUsuario, chat.id_amigo);
                        suscribirseAlChat(chat.id_amigo);
                    });
                    chatList.appendChild(div);
                });

                if (data.length) {
                    const primerChat = chatList.querySelector(".chat-activo");
                    primerChat.classList.add("activo");
                    seleccionarChat(primerChat.dataset.idAmigo);
                    cargarMensajes(idUsuario, primerChat.dataset.idAmigo);
                    suscribirseAlChat(primerChat.dataset.idAmigo);
                }
            })
            .catch(error => {
                console.error(error);
                chatList.innerHTML = "<p>Error al cargar chats.</p>";
            });
    }

    function seleccionarChat(idReceptor) {
        document.getElementById('id_receptor').value = idReceptor;
        mensajeInput.value = "";
        archivoInput.value = "";
    }

    function cargarMensajes(idEmisor, idReceptor) {
        fetch(`/chat/cargar_mensajes.php?id_emisor=${idEmisor}&id_receptor=${idReceptor}`)
            .then(res => {
                if (!res.ok) throw new Error("Error en la respuesta del servidor");
                return res.json();
            })
            .then(data => {
                chatMensajes.innerHTML = "";
                data.forEach(msg => {
                    agregarMensajeAlChat(msg);
                });
                chatMensajes.scrollTop = chatMensajes.scrollHeight;
            })
            .catch(error => {
                // console.error(error);
            });
    }

    function agregarMensajeAlChat(msg) {
        if (!msg) return;

        // si no hay texto ni contenido, no hacer nada
        if ((!msg.mensaje || msg.mensaje.trim() === "") && !msg.contenido) {
            return;
        }

        const div = document.createElement("div");
        div.className = `mensaje ${msg.es_mensaje_yo ? "mensaje-yo" : "mensaje-otro"}`;

        if (msg.mensaje && msg.mensaje.trim() !== "") {
            const p = document.createElement("p");
            p.innerText = msg.mensaje;
            div.appendChild(p);
        }

        if (msg.contenido) {
            if (msg.tipo === "imagen") {
                const img = document.createElement("img");
                img.src = msg.contenido;
                img.alt = "Imagen";
                img.className = "imagen-mensaje";
                div.appendChild(img);
            } else if (msg.tipo === "archivo") {
                const a = document.createElement("a");
                a.href = msg.contenido;
                a.download = msg.nombre_archivo || "archivo";
                a.textContent = msg.nombre_archivo || "Descargar archivo";
                div.appendChild(a);
            }
        }

        chatMensajes.appendChild(div);
        chatMensajes.scrollTop = chatMensajes.scrollHeight;
    }


    formulario.addEventListener("submit", e => {
        e.preventDefault();

        const formData = new FormData(formulario);

        fetch("/chat/enviar_mensaje.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.text())
            .then(async text => {
                try {
                    const data = JSON.parse(text);
                    if (data.error) return;

                    // Mostrar en el chat abierto
                    agregarMensajeAlChat({
                        id_mensaje: data.id_mensaje,
                        id_emisor: data.id_emisor,
                        id_receptor: data.id_receptor,
                        es_mensaje_yo: true,
                        creado_en: data.creado_en,
                        mensaje: mensajeInput.value.trim(),
                    });

                    const idAmigo = data.id_receptor;
                    let chatDiv = document.querySelector(`.chat-activo[data-id-amigo="${idAmigo}"]`);

                    if (chatDiv) {
                        // Actualiza último mensaje
                        const ultimoSmall = chatDiv.querySelector('small');
                        if (ultimoSmall) {
                            ultimoSmall.textContent = mensajeInput.value.trim() || "[Archivo o imagen]";
                        }
                        // Sube el chat al inicio
                        chatList.prepend(chatDiv);
                    } else {
                        // Obtener datos del usuario y crearlo en la lista
                        const res = await fetch(`/chat/obtener_usuario.php?id=${idAmigo}`);
                        const usuario = await res.json();
                        if (!usuario.error) {
                            agregarChatNuevo(usuario);
                        }
                    }

                    mensajeInput.value = "";
                    archivoInput.value = "";
                } catch (e) {
                    // console.error("Error procesando respuesta:", e);
                }
            })
            .catch(() => { });
    });
    cargarChats();
});
