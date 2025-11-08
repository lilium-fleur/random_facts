<?php

namespace App\Services\Prompt;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\HttpClient;

class PromptToExternalAiService implements PromptToAiService
{
    public function prompt(): array
    {
        try {
            $response = HttpClient::create()->request('POST', 'https://api.groq.com/openai/v1/responses', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                    'Content-Type' => 'application/json',
                ],

                'json' => [
                    'model' => 'llama-3.3-70b-versatile',
                    'input' => 'ou are a JSON fact generator. Your only job is to output valid JSON.

                    Generate EXACTLY 30 random interesting facts.

                    Each object must have exactly these 3 keys:
                    - "fact": a short, verified, interesting fact in English (max 4 sentences)
                    - "category": one of: science, history, nature, technology, space, animal, food, human, geography, misc
                    - "source": short source like "NASA", "BBC", "Wikipedia", "general knowledge"

                    Example output (DO NOT COPY, generate new facts):
                    [
                      {"fact": "Honey never spoils.", "category": "food", "source": "BBC"},
                      {"fact": "Octopuses have three hearts.", "category": "animal", "source": "general knowledge"}
                    ]

                    **CATEGORIES (use exactly one per fact, no repeats):**
                    science, history, nature, technology, space, animal, food, human, geography, misc

                    **Rules (FOLLOW STRICTLY):**
                    1. Output **ONLY** a valid JSON array of **exactly objects**.
                    2. Each object has **exactly 3 keys**: `fact`, `category`, `source`.
                    3. `fact`: 4–5 sentences, in English, fascinating and accurate.
                    4. `category`: **one of the 10 above**, **no duplicates**.
                    5. `source`: short, like "NASA", "BBC", "Wikipedia", "Smithsonian", "National Geographic", "general knowledge", etc what you want.
                    6. **NO markdown, NO code blocks, NO extra text, NO explanations**.
                    7. Start with `[` and end with `]`.
                    8. **All facts must be different and cover all topics** — no two about animals, no two about space, etc.',
                    'temperature' => 0.8,
                ],
            ]);

            $output = $response->toArray()['output'];

            $rawFacts = Arr::first($output, fn ($item) => isset($item['role']) && $item['role'] === 'assistant')['content'][0]['text'];

            return json_decode($rawFacts, true);
        } catch (\Throwable $e) {
            Log::error($e);

            return [];
        }
    }
}
