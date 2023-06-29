<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MesaggeController extends Controller
{
    public function index()
    {
        $chats = Chat::orderByDesc('last_message_received')->paginate(10);
        return response()->json($chats);
    }
}
