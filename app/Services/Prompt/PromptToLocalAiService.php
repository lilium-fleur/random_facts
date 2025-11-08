<?php

namespace App\Services\Prompt;

use Symfony\Component\HttpClient\HttpClient;

class PromptToLocalAiService implements PromptToAiService
{
    public function prompt(): array
    {
        $client = HttpClient::create();

        $response = $client->request('POST', 'http://127.0.0.1:11434/api/generate', [
            'json' => [
                'model' => 'gemma3:1b',
                'format' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'fact' => ['type' => 'string'],
                            'category' => ['type' => 'string'],
                            'source' => ['type' => 'string']
                        ]
                    ]
                ],
                'prompt' => '
                    You are a JSON fact generator. Your only job is to output valid JSON.

                    Generate EXACTLY 20 random interesting facts.

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
                    1. Output **ONLY** a valid JSON array of **exactly 10 objects**.
                    2. Each object has **exactly 3 keys**: `fact`, `category`, `source`.
                    3. `fact`: 1–3 sentences, in English, fascinating and accurate.
                    4. `category`: **one of the 10 above**, **no duplicates**.
                    5. `source`: short, like "NASA", "BBC", "Wikipedia", "Smithsonian", "National Geographic", "general knowledge".
                    6. **NO markdown, NO code blocks, NO extra text, NO explanations**.
                    7. Start with `[` and end with `]`.
                    8. **All facts must be different and cover all topics** — no two about animals, no two about space, etc.
                    ',
                'stream' => false,
                'options' => [
                    'temperature' => 0.8,
                ],
            ],
        ]);

        return $response->toArray()['response'];
    }
}
