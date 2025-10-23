<?php

namespace App\Http\Controllers;

use App\Models\OrderTicket;
use Illuminate\Http\Request;

class OrderTicketController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'message' => 'nullable|string|max:5000',
        ]);

        OrderTicket::create($validated);

        return response()->json(['message' => 'Заявка успешно отправлена'], 200);
    }
    public function index()
    {
        return OrderTicket::latest()->get();
    }
}
