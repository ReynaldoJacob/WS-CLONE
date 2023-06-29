<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Contact;
use App\Models\RepeatedMessage;
use Illuminate\Support\Facades\Validator;
use DB;

class ChatController extends Controller
{
    const ENVIADO = 1;
    const RECIBIDO_EN_SERVIDOR = 2;
    const USUARIO_RECIBIDO = 3;
    const LEIDO = 4;

    /**
     * Esta es una función que nos da los chats
     *
     * @return object Los chats con su id, el nombre del contacto, foto, contenido del ultimo mensaje y fecha, .
     */
    public function getChats()
    {
        try {
            $chats = DB::table('chats as c')
            ->select('cs.id','cs.name','cs.url_avatar', 'm.content as ultimo_mensaje', DB::raw('DATE_FORMAT(created_at, "%h:%i %p") AS fecha'))
            ->join('messages as m', function ($join) {
                $join->on('m.id', '=', DB::raw('(
                    SELECT id
                    FROM messages
                    WHERE chat_id = c.id
                    ORDER BY created_at DESC
                    LIMIT 1
                )'));
            })
            ->join('contacts as cs', 'cs.id', 'c.contact_id')
            ->orderBy('fecha', 'desc')
            ->get();
    
            return response()->json([
                'success' => true,
                'message' => 'Petiicion correcta',
                'data' => $chats
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }

    /**
     * Esta es una función nos muestra los mensajes de un chat
     *
     * @param int $id El id del contacto.
     * @return object Muestra todos los mensajes que tiene un chat.
     */
    public function chatMessages($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input',
                'message' => $validator->errors()->first(),
            ], 400);
        }
        
        $messages = Chat::select("m.id","chats.contact_id","m.content",DB::raw('DATE_FORMAT(created_at, "%h:%i %p") as fecha'),'m.status')->
            join("messages as m", "m.chat_id", "chats.id")
            ->orderBy("created_at", "asc")
            ->where("chats.contact_id", $id)->get();

        return response()->json($messages);
    }

    /**
     * Esta es una función que almacena el nuevo mensaje.
     *
     * @param int $id El id del chat.
     * @param int $message El contenido del mensaje.
     * @return 
     */
    public function saveMessage($id, $message){
        try {
            $newMessage = new Message;
            $newMessage->chat_id = $id;
            $newMessage->content = $message;
            $newMessage->status = ChatController::RECIBIDO_EN_SERVIDOR;
            $newMessage->forwarded_message = false;
            $newMessage->save();
    
            return $newMessage;
        } catch (\Exception $e) {
            // Manejar la excepción
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Esta es una función que permite enviar el mensaje
     *
     * @param int $contact_id El id del contacto.
     * @param int $message Contenido del mensaje.
    */
    public function sendMessage(Request $request){
        $res;
      
        try {
            $validatedData = $request->validate([
                'contact_id' => 'required',
                'message' => 'required'
            ]);
    
            $chat = Chat::where("contact_id", $request->contact_id)->first();

            if ($chat === null) {
                $newChat = New Chat;
                $newChat->contact_id = $request->contact_id;
                $newChat->save();
    
               $res = $this->saveMessage($newChat->id, $request->message);
    
            } else {
                $res = $this->saveMessage($chat->id, $request->message);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registro guardado y enviado',
                'data' => $res
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Esta es una función para obtener la lista de contactos ordenada alfabéticamente..
     *
     * @return object Regresa un objecto con la lista de contactos  mostrado el nombre del contacto, foto e info
     */
    public function showContacts(){
        $contacts = Contact::select("*")->orderBy("name")->get();

        return response()->json($contacts);
    }

    /**
     * Esta es una función para reenviar los mensajes.
     *
     * @param array $contactsChecked array con los id de los contactos a los que se enviaran
     * @param array $messagesChecked array con los id de los mensajes para almacenar
     */
    public function messageResend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contactsChecked' => 'required|array',
            'messagesChecked' => 'required|array',
        ]);
    
        if ($validator->fails()) {
            // La validación falló, maneja el error aquí
            return response()->json(['error' => $validator->errors()], 400);
        }

        foreach ($request->contactsChecked as $contact) {   
            $contactChat = Chat::where("contact_id", $contact)->first();

            if(!$contactChat){
                $newChat = New Chat;
                $newChat->contact_id = $contact;
                $newChat->save();
            } 

            foreach ($request->messagesChecked as $message) {
                $contentMessage = Message::where("id", $message)->first();
                $RepeaetedMessage = Message::create([
                    'chat_id' => $contactChat ? $contactChat->id : $newChat->id,
                    'content' => $contentMessage->content, 
                    'status' => ChatController::RECIBIDO_EN_SERVIDOR,
                    'forwarded_message' => true 
                ]);
            }
        }
    }

    /**
     * Esta es una función para actualizar el estado del mensaje.
     *
     * @param int $id id del mensaje.
     * @param array $message cantida de mensajes a actualizar.
     */
    public function updateStatusMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable',
            'messages' => 'array'
        ]);
    
        if ($validator->fails()) {
            // Manejar el error de validación
            return response()->json(['error' => $validator->errors()], 400);
        }
       
       if($request->id){
            $messageUpdate = Message::find($request->id);
            $messageUpdate->status = ChatController::USUARIO_RECIBIDO;
            $messageUpdate->save();
       } else {
            foreach ($request->messages as $item) {
              if(isset($item['id'])){
                $messageUpdate = Message::find($item['id']);
                $messageUpdate->status = ChatController::LEIDO;
                $messageUpdate->save();
              }
            }       
       }      
    }
}
