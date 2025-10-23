<?php

namespace App\Http\Controllers;

use App\Models\Diploma;
use Illuminate\Http\Request;

class DiplomaController extends Controller
{
    public function verify($document_number)
{
    $diploma = Diploma::where('document_number', $document_number)->with('subject')->first();

    if (!$diploma) {
        return response()->json([
            'success' => false,
            'message' => 'Диплом с таким номером не найден.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'firstname' => $diploma->firstname,
            'lastname' => $diploma->lastname,
            'score' => $diploma->score,
            'subject' => $diploma->subject->name,
            'created_at' => $diploma->created_at->format('Y-m-d'),
        ]
    ]);
}

}
