<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{

    public function ai_assistant_chat(Request $request)
    {
        $userMessage = $request->input('message');

        $response = Http::withToken("")
            ->post('https://api.together.xyz/v1/chat/completions', [
                'model' => 'meta-llama/Llama-3.3-70B-Instruct-Turbo-Free',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are ডাক্তার স্বপ্না, a caring Bangladeshi Muslim female virtual ai doctor. Respond in Bangla. You can only answer questions about yourself or medical/health-related topics. If asked about anything else, reply: "দুঃখিত, আমি এই বিষয়ে উত্তর দিতে পারি না।" Provide basic suggestions for common health issues, avoid diagnosing serious conditions, and always be polite and clear. If you do not know the answer, say "দুঃখিত, আমি জানি না।" and do not provide any other information.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

        return response()->json([
            'reply' => $response->json()['choices'][0]['message']['content']
        ]);
    }


}
