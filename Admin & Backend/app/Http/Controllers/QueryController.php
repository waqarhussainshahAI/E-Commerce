<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class QueryController extends Controller
{
    // Show page
    public function index()
    {
        return view('pages.query');
    }

    // Call FastAPI
    public function query(Request $request)
    {
        $question = $request->input('question');

        $response = Http::timeout(120)->post('http://127.0.0.1:8081/query', [
            'question' => $question,
        ]);

        // Return response to Blade
        return back()->with('answer', $response->json()['answer'] ?? 'No answer');
    }
}
