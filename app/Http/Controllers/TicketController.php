<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\OrderTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function store(Request $request)
     {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'text'  => 'required|string|max:5000',
        ]);

        $ticket = Ticket::create($validated);

        return response()->json([
            'message' => 'Ваше сообщение отправлено успешно!',
            'ticket'  => $ticket
        ], 201);
    }

 public function getAllData()
    {
        $tickets = Ticket::latest()->get();
        $orderTickets = OrderTicket::latest()->get();
        $emails = Email::latest()->get();

        return response()->json([
            'tickets' => $tickets,
            'orderTickets' => $orderTickets,
            'emails' => $emails,
        ]);
    }
}
