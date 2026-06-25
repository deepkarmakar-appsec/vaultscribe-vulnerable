<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    //fake api key 
    private $openRouterKey = 'hfdajfdja jjvbafdjfnhdknbfdjhjkreh743hjkrheqhrh'; //fake api key leaked for learning 

    public function generate(Request $request)
    {
        // 1. Validation
        $request->validate([
            'description' => 'required|string|max:2000'
        ]);
    
        // 2. Variable ka use karein
        $apiKey = $this->openRouterKey;
        
        $url = "https://openrouter.ai/api/v1/chat/completions";
    
        try {
            // 3. Request
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'HTTP-Referer'  => 'http://localhost',
                'X-Title'       => 'VaultScribe',
                'Content-Type'  => 'application/json',
            ])->timeout(30)->post($url, [
                'model' => 'openai/gpt-oss-120b:free',
                'messages' => [
                    ['role' => 'user', 'content' => "Title and Summary for: {$request->description}"]
                ]
            ]);
        
            if ($response->failed()) {
                return response()->json(['result' => 'API Error', 'details' => $response->body()], 500);
            }
        
            return response()->json(['result' => $response->json('choices.0.message.content')]);

        } catch (\Exception $e) {
            return response()->json(['result' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
}