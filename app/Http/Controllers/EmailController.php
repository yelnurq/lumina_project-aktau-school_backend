<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;

class EmailController extends Controller
{
     public function store(Request $request)
     {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = Email::create($validated);

        return response()->json([
            'message' => 'Ваше сообщение отправлено успешно!',
            'email'  => $email
        ], 201);
    }

    public function index()
    {
        return Email::latest()->get();
    }
}
