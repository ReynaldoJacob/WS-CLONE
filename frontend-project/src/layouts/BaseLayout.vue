<template>
<q-layout view="lHh LpR lFr" container class="background-layout">
    <div class="app-container">
        <q-drawer content-class="drawer-content" :width="400" show-if-above side="left">
            <div class="q-pa-md q-gutter-sm toolbar-menu-avatar">
                <div>
                    <q-avatar size="40px" color="primary" text-color="white"><img src="https://media.sproutsocial.com/uploads/2022/06/profile-picture.jpeg">
                    </q-avatar>
                </div>
                <div>
                    <q-btn flat align="right" class="btn-fixed-width" size="11px" style="color: #54656f;" icon="fa-solid fa-users" />
                    <q-btn flat align="right" class="btn-fixed-width" size="11px" style="color: #54656f;" icon="fa-regular fa-circle" />
                    <q-btn @click="drawerLeft = !drawerLeft" flat align="right" class="btn-fixed-width" size="11px" style="color: #54656f;" icon="fa-solid fa-message" />
                    <q-btn flat align="right" class="btn-fixed-width" size="11px" style="color: #54656f;" icon="more_vert" />
                </div>
            </div>
            <q-list class="chat-list">
                <div style="margin: 0px 36px;">
                    <q-input rounded standout bottom-slots label="Busca un chat o inicia uno nuevo">
                        <template v-slot:prepend>
                            <q-icon name="fa-solid fa-magnifying-glass" />
                        </template>
                    </q-input>
                </div>
                <q-item class="chat-individual" v-for="chat in chats" :key="chat.id" clickable>
                    <q-item-section avatar>
                        <q-avatar size="50px">
                            <img :src="chat.url_avatar">
                        </q-avatar>
                    </q-item-section>
                    <q-item-section @click="changeChat(chat.id, 1)">
                        <q-item-label lines="1" class="chat-title">{{ chat.name }}</q-item-label>
                        <q-item-label lines="1" caption>{{ chat.ultimo_mensaje }}</q-item-label>
                    </q-item-section>
                    <q-item-label lines="1" caption>{{ chat.fecha }}</q-item-label>
                </q-item>
            </q-list>
        </q-drawer>
        <div v-if="startChat">
            <q-header class="bg-teal-6 text-white" style="left: 400px !important; ">
                <q-toolbar>
                    <q-avatar size="45px">
                        <img :src="avatar">
                    </q-avatar>
                    <q-toolbar-title class="text-contact">
                        {{user}}
                    </q-toolbar-title>
                    <div>
                        <q-btn flat align="right" class="btn-fixed-width" size="10px" style="color: #54656f;" icon="fa-solid fa-magnifying-glass" />
                        <q-btn flat align="right" class="btn-fixed-width" size="10px" style="color: #54656f;" icon="more_vert" />
                    </div>
                </q-toolbar>
            </q-header>
            <q-page-container>
                <div class="chat-container">
                    <div class="chat-messages" style="width: 100%;">
                        <div v-for="message in messages" :key="message.id" class="chat-message" :class="{ 'chat-message-self': message.isSelf }">
                            <q-dialog v-model="inception">
                                <q-card style="width: 551px; max-width: 31vw;">
                                    <q-card style="background: #008069" class="my-card text-white">
                                        <q-card-section>
                                            <div class="text-h6"></div>
                                            <div class="text-subtitle2"></div>
                                        </q-card-section>
                                        <q-card-section>
                                        </q-card-section>
                                        <q-card-actions>
                                            <q-btn v-close-popup flat size="15px" icon="fa-solid fa-xmark" />
                                            <q-item-label lines="1" class="chat-title">Reenviar mensaje a</q-item-label>
                                        </q-card-actions>
                                    </q-card>
                                    <q-card-section class="q-pt-none">
                                        <div class="q-pa-sm">
                                            <q-list class="chat-list">
                                                <q-item class="chat-individual" v-for="contact in contacts" :key="contact.id" clickable>
                                                    <div v-if="showCheckbox" class="div-checkbox">
                                                        <input type="checkbox" :id="contact.id" :value="contact.id" v-model="checkedContacts">
                                                        <label hidden :for="contact.id">{{contact.id}}</label>
                                                    </div>
                                                    <q-item-section avatar>
                                                        <q-avatar size="50px">
                                                            <img :src="contact.url_avatar">
                                                        </q-avatar>
                                                    </q-item-section>
                                                    <q-item-section @click="changeChat(contact.id, 2)">
                                                        <q-item-label lines="1" class="text-contact"> {{ contact.name }}</q-item-label>
                                                        <q-item-label lines="1" caption>{{ contact.info }}</q-item-label>
                                                    </q-item-section>
                                                </q-item>
                                            </q-list>
                                        </div>
                                    </q-card-section>
                                    <q-card-actions align="right" class="text-primary">
                                        <q-btn style="color: #54656f;" v-close-popup flat @click="resendMessage(idNewMessage)" icon="send" />
                                    </q-card-actions>
                                </q-card>
                            </q-dialog>
                            <div v-if="showCheckbox" class="checkbox-wrapper-31">
                                <input checked="false" type="checkbox" :id="message.id" :value="message.id" v-model="checkedMessages">
                                <svg viewBox="0 0 35.6 35.6">
                                    <circle class="background" cx="17.8" cy="17.8" r="17.8"></circle>
                                    <circle class="stroke" cx="17.8" cy="17.8" r="14.37"></circle>
                                    <polyline class="check" points="11.78 18.12 15.55 22.23 25.17 12.87"></polyline>
                                </svg>
                            </div>
                            <div class="message-content">
                                {{ message.content }}
                                <i style="padding: 2px 3px;" class="fa-solid fa-chevron-down arrow-resend"></i>
                                <div style="display:flex; justify-content: end">
                                    <span class="message-time">{{ message.fecha}}</span>
                                    <br>
                                    <i v-if="message.status == 'Enviando'" style="padding: 2px 3px;" class="fa-regular fa-clock"></i>
                                    <i v-if="message.status == 'Recibido en servidor'" style="padding: 2px 3px;" class="fa-solid fa-check"></i>
                                    <i v-if="message.status == 'Usuario recibió mensaje'" style="padding: 2px 3px;" class="fa-solid fa-check-double"></i>
                                    <i v-if="message.status == 'Leído'" style="padding: 2px 3px; color:blue" class="fa-solid fa-check-double"></i>
                                </div>
                                <q-menu icon="menu-icon" ref="menu" :id="'menu-' + message.id" class="menu-dropdown">
                                    <q-list>
                                        <q-item>
                                            <q-item-section @click="showCheckbox = !showCheckbox">
                                                Reenviar
                                            </q-item-section>
                                        </q-item>
                                        <!-- Agrega más opciones de menú aquí -->
                                    </q-list>
                                    <template v-slot:trigger>
                                        <q-btn flat dense icon="mdi-dots-vertical" round color="primary" @click="toggleMenu(message)" />
                                    </template>
                                </q-menu>
                            </div>
                        </div>
                    </div>
                </div>
            </q-page-container>
            <q-footer class="chat-input text-white" style="left: 400px !important; ">
                <q-toolbar>
                    <q-input @keyup.enter="sendMessage(idNewMessage)" dense outlined placeholder="Escribe un mensaje" class="message-input" v-model="newMessage" />
                    <q-btn v-if="!showCheckbox" @click="sendMessage(idNewMessage)" dense flat round icon="send" class="send-button" />
                    <q-btn v-else @click="inception = true" dense flat round icon="fa-solid fa-share" class="send-button" />
                </q-toolbar>
            </q-footer>
        </div>
        <q-drawer content-class="drawer-content" v-model="drawerLeft" :width="400" :breakpoint="500" bordered>
            <q-scroll-area class="fit">
                <q-card style="background: #008069" class="my-card text-white">
                    <q-card-section>
                        <div class="text-h6"></div>
                        <div class="text-subtitle2"></div>
                    </q-card-section>
                    <q-card-section>
                    </q-card-section>
                    <q-card-actions>
                        <q-btn @click="drawerLeft = !drawerLeft" flat size="15px" icon="fa-solid fa-arrow-left" />
                        <q-item-label lines="1" class="chat-title">Nuevo chat</q-item-label>
                    </q-card-actions>
                </q-card>
                <div class="q-pa-sm">
                    <q-list class="chat-list">
                        <q-item class="chat-individual" v-for="contact in contacts" :key="contact.id" clickable>
                            <q-item-section avatar>
                                <q-avatar size="50px">
                                    <img :src="contact.url_avatar">
                                </q-avatar>
                            </q-item-section>
                            <q-item-section @click="changeChat(contact.id, 2)">
                                <q-item-label lines="1" class="text-contact"> {{ contact.name }}</q-item-label>
                                <q-item-label lines="1" caption>{{ contact.info }}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </div>
            </q-scroll-area>
        </q-drawer>
    </div>
</q-layout>
</template>

<script>
import {
    ref
} from 'vue'
import axios from 'axios';
import {
    fasFont
} from '@quasar/extras/fontawesome-v5'

export default {
    setup() {
        const leftDrawerOpen = ref(false)
        const chats = ref([]);
        const contacts = ref([]);
        const messages = ref([]);
        const chatInfo = {};
        const idNewMessage = ref("");
        const user = ref("");
        const avatar = ref("");
        const timer = ref("");

        return {
            chats,
            contacts,
            fasFont,
            leftDrawerOpen,
            toggleLeftDrawer() {
                leftDrawerOpen.value = !leftDrawerOpen.value
            },
            options: [{
                label: "Reenviar"
            }],
            checkedMessages: ref([]),
            checkedContacts: ref([]),
            drawerLeft: ref(false),
            showCheckbox: ref(false),
            inception: ref(false),
            startChat: ref(false),
            messages,
            idNewMessage: '',
            newMessage: '',
            user,
            avatar,
            timer
        }
    },
    mounted() {
        this.getMessage();
        this.getContact();
    },
    methods: {

        callFunction: function () {
            let bind = this; // Referencia al objeto actual

            const userData = {
                messages: bind.messages // Crear un objeto `userData` con la propiedad `messages`
            };

            setTimeout(() => {
                // Función de devolución de llamada que se ejecutará después de 3 segundos

                // Realizar una solicitud POST utilizando Axios
                axios.post('http://127.0.0.1:8000/api/v1/messages/statusUpdate', userData)
                    .then(function (response) {
                        // Función de devolución de llamada si la solicitud se realiza correctamente

                        // Llamar a la función `changeChat` con los argumentos `bind.messages[0].contact_id` y `3
                        bind.changeChat(bind.messages[0].contact_id, 3)
                    })
                    .catch(function (error) {
                        // Función de devolución de llamada si la solicitud produce un error
                        console.log(error);
                    });
            }, 3000); // Retrasar la ejecución de la función de devolución de llamada durante 3 segundos

        },

        /**
         * Realiza una petición Axios con el método, URL y objeto especificados, y ejecuta una acción dependiendo del resultado.
         * @param {string} method - Método de la petición (por ejemplo, 'GET', 'POST', 'PUT', 'DELETE').
         * @param {string} url - URL a la cual se realiza la petición.
         * @param {object} object - Objeto de datos enviado en la petición.
         * @param {string} accion - Acción a realizar dependiendo del resultado de la petición.
         */
        peticionAxios: function (method, url, object, accion) {
            let bind = this; // Referencia al objeto actual
            axios({
                    method: method, // Método de la petición
                    url: url, // URL de la petición
                    data: object, // Objeto de datos enviado en la petición
                    responseType: 'json' // Tipo de respuesta esperado (JSON en este caso)
                })
                .then(function (response) {
                    // Función de devolución de llamada si la petición se realiza correctamente

                    switch (accion) {
                        case 'mostrarChats':
                            // Realizar acción 'mostrarChats': asignar los datos de respuesta a la propiedad 'chats'
                            bind.chats = response.data.data
                            break;
                        case 'mostrarContactos':
                            // Realizar acción 'mostrarContactos': asignar los datos de respuesta a la propiedad 'contacts'
                            bind.contacts = response.data
                            break;
                        case 'enviarMensaje':
                            // Realizar acción 'enviarMensaje'
                            bind.getMessage(); // Obtener los mensajes
                            bind.changeChat(object.contact_id, 1); // Cambiar el chat al contacto especificado
                            console.log(response.data.data.id)
                            if (response.status >= 200 && response.status < 300) {
                                // Si la petición original fue exitosa (código de estado entre 200 y 299),
                                // realizar una nueva solicitud POST a 'http://127.0.0.1:8000/api/v1/messages/statusUpdate'
                                // con el ID del mensaje enviado
                                axios.post('http://127.0.0.1:8000/api/v1/messages/statusUpdate', {
                                        id: response.data.data.id,
                                    })
                                    .then(function (response) {
                                        bind.changeChat(object.contact_id, 1);
                                    })
                                    .catch(function (error) {
                                        console.log(error);
                                    });
                            }
                            break;
                        default:
                            break;
                    }
                });
        },

        obtenerHora() {
            // Obtener la fecha y hora actual
            const fechaActual = new Date();

            // Obtener la hora actual en formato de 12 horas
            const horaActual = fechaActual.getHours() % 12 || 12;

            // Obtener los minutos actuales
            const minutosActuales = fechaActual.getMinutes();

            // Obtener el período (AM o PM)
            const periodo = fechaActual.getHours() < 12 ? 'AM' : 'PM';

            // Formatear la hora y minutos en formato de 12 horas con ceros a la izquierda
            const horaConMinutos = `${horaActual.toString().padStart(2, '0')}:${minutosActuales.toString().padStart(2, '0')} ${periodo}`;

            return horaConMinutos; // Ejemplo de salida: "03:30 PM" (para las 3:30 PM)
        },

        /**
         * Obtiene los mensajes de chat mediante una petición GET y actualiza la lista de chats.
         */
        getMessage() {
            var url = "http://127.0.0.1:8000/api/v1/chats"; // URL de la petición GET
            var method = "get"; // Método de la petición
            var object = {}; // Objeto de datos enviado en la petición (vacío en este caso)
            var accion = "mostrarChats"; // Acción a realizar dependiendo del resultado de la petición
            this.peticionAxios(method, url, object, accion); // Realizar la petición mediante la función peticionAxios
        },

        getContact() {
            var url = "http://127.0.0.1:8000/api/v1/contacts"; // URL de la petición GET
            var method = "get"; // Método de la petición
            var object = {}; // Objeto de datos enviado en la petición (vacío en este caso)
            var accion = "mostrarContactos"; // Acción a realizar dependiendo del resultado de la petición
            this.peticionAxios(method, url, object, accion); // Realizar la petición mediante la función peticionAxios
        },

        /**
         * Envía un mensaje a un chat específico mediante una petición POST y actualiza la lista de mensajes.
         * @param {integer} id - ID del contacto al que se enviará el mensaje.
         */
        sendMessage(id) {
            var url = "http://127.0.0.1:8000/api/v1/chats/messages"; // URL de la petición POST
            var method = "post"; // Método de la petición
            var object = {
                message: this.newMessage, // Contenido del mensaje a enviar
                contact_id: id // ID del contacto al que se enviará el mensaje
            };
            var accion = "enviarMensaje"; // Acción a realizar dependiendo del resultado de la petición

            this.messages.push({
                content: this.newMessage,
                fecha: this.obtenerHora(),
                status: 'Enviando'
            }); // Agregar el mensaje a la lista de mensajes con estado "Enviando"

            this.peticionAxios(method, url, object, accion); // Realizar la petición mediante la función peticionAxios
        },

        /**
         * Reenvía los mensajes seleccionados mediante una petición POST y actualiza la lista de mensajes.
         */
        resendMessage() {
            axios.post('http://127.0.0.1:8000/api/v1/messages/resend', {
                    messagesChecked: this.checkedMessages, // Mensajes seleccionados para reenvío
                    contactsChecked: this.checkedContacts // Contactos asociados a los mensajes seleccionados
                })
                .then(response => {
                    this.getMessage() // Actualiza la lista de mensajes después de reenviar los mensajes seleccionados
                })
                .catch(error => {
                    this.error = 'Error al consultar la API.' // Manejo de error en caso de fallo en la petición
                    console.error(error) // Registro del error en la consola
                })
        },

        /**
         * Cambia la conversación actual a la conversación con el ID especificado.
         * @param {number} id - ID de la conversación o contacto.
         * @param {number} type - Tipo de conversación: 1 para chats, 2 para contactos, 3 para cambio manual.
         */
        changeChat(id, type) {
            this.startChat = true; // Marca la conversación como iniciada
            this.checkedMessages = []; // Reinicia los mensajes seleccionados
            this.showCheckbox = false; // Oculta las casillas de verificación
            axios.get('http://127.0.0.1:8000/api/v1/chats/' + id + '/messages')
                .then(response => {
                    this.messages = response.data // Actualiza la lista de mensajes con la respuesta de la API
                    if (type != 3) {
                        this.callFunction(); // Llama a la función "callFunction()" si el tipo no es 3
                    }
                })
                .catch(error => {
                    this.loading = false
                    this.error = 'Error al consultar la API.'
                    console.error(error)
                })
            // Determina el array de origen según el tipo de conversación
            if (type == 1) {
                var array1 = this.chats;
            } else {
                var array1 = this.contacts;
            }
            // Busca el elemento con el ID especificado en el array de origen
            const found = array1.find(element => element.id === id);

            this.user = found.name // Establece el nombre del usuario de la conversación
            this.avatar = found.url_avatar // Establece el URL del avatar del usuario de la conversación
            this.idNewMessage = found.id // Establece el ID del nuevo mensaje
        }
    }
}
</script>


<style lang="sass" scoped>
  .div-checkbox
    display: flex
    margin: 0px 15px
  
  .toolbar-menu-avatar
    height: 73px
    background:#f0f2f5 
    display: flex
    justify-content: space-between
    align-items: center

  .arrow-resend
     opacity: 0
     transition: all .3s ease
  
  .message-content:hover .arrow-resend
    opacity: 1

  .header-container
    flex: 0 0 auto

  .chat-input
    background: #f0f2f5

  .q-page-container
    padding: 0

  .q-header
    height: 64px

  .q-header .q-toolbar
    height: 100%
    padding: 0 16px

  .q-footer
    height: 56px

  .q-footer .q-toolbar
    height: 100%
    padding: 0 16px

  .text-white
    color: #fff

  .bg-teal-6
    background-color: #f0f2f5 !important

  .bg-grey-8
    background-color: #f0f2f5

  .text-contact
    color: black
    font-size: large
    font-weight: 300

  .message-input
    background: white
    flex: 1

  .send-button
    margin-left: 8px
    color: #54656f

  .chat-list
    margin-top: 16px
    
  .chat-individual
    border: 1px solid #f0f2f5

  .chat-title
    font-weight: bold

  .chat-container
    display: flex
    flex-direction: column
    height: calc(100% - 120px)

  .chat-messages
    flex: 1
    overflow-y: auto
    padding: 16px
    display: grid
    justify-content: end

  .modal-custom .q-dialog__inner 
    background-color: transparent !important
    box-shadow: none !important

  .chat-message
    display: flex
    flex-direction: column
    margin-bottom: 8px
    max-width: 100%
    align-items: flex-end

  .chat-message-self
    align-items: flex-end

  .message-content
    background-color: #d9fdd3
    padding: 8px
    border-radius: 10px
    box-shadow: 0px 2px 7px -4px

  .message-time
    font-size: 12px
    color: #888

  .background-layout
    background: url(https://i.pinimg.com/originals/8c/98/99/8c98994518b575bfd8c949e91d20548b.jpg) 

    .app-container
    display: flex
    flex-direction: column
    height: 100vh
    
  .checkbox-message
    width: 871px
    position: relative
    top: 40px

  .checkbox-wrapper-31:hover .check 
    stroke-dashoffset: 0

  .checkbox-wrapper-31 
    position: relative
    display: inline-block
    width: 20px
    height: 20px

  .checkbox-wrapper-31 .background 
    fill: #ccc
    transition: ease all 0.6s
    -webkit-transition: ease all 0.6s

  .checkbox-wrapper-31 .stroke 
    fill: none
    stroke: #fff
    stroke-miterlimit: 10
    stroke-width: 2px
    stroke-dashoffset: 100
    stroke-dasharray: 100
    transition: ease all 0.6s
    -webkit-transition: ease all 0.6s

  .checkbox-wrapper-31 .check 
    fill: none
    stroke: #fff
    stroke-linecap: round
    stroke-linejoin: round
    stroke-width: 2px
    stroke-dashoffset: 22
    stroke-dasharray: 22
    transition: ease all 0.6s
    -webkit-transition: ease all 0.6s

  .checkbox-wrapper-31 input[type=checkbox] 
    position: absolute
    width: 100%
    height: 100%
    left: 0
    top: 0
    margin: 0
    opacity: 0
    -appearance: none
    -webkit-appearance: none


  .checkbox-wrapper-31 input[type=checkbox]:hover 
    cursor: pointer

  .checkbox-wrapper-31 input[type=checkbox]:checked + svg .background 
    fill: #6cbe45

  .checkbox-wrapper-31 input[type=checkbox]:checked + svg .stroke 
    stroke-dashoffset: 0

  .checkbox-wrapper-31 input[type=checkbox]:checked + svg .check 
      stroke-dashoffset: 0 
</style>