<?php

namespace App\Services;

use App\Models\Fact;
use App\Services\Prompt\PromptToAiService;
use App\Services\Prompt\PromptToLocalAiService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\HttpClient;

class FactGenerationService {

    public function __construct(
        private PromptToAiService $promptToAiService,
    )
    {
    }

    /**
     * @throws ConnectionException
     */
    public function generateFacts(): void
    {
        try {
            $facts = $this->promptToAiService->prompt();;

            foreach ($facts as $rawFact) {
                if (empty($rawFact['fact'])) continue;

                $embedding = $this->getEmbedding($rawFact['fact']);

                if (empty($embedding) || $this->isDuplicate($embedding)) continue;

                Fact::create([
                    'text' => $rawFact['fact'],
                    'category' => $rawFact['category'],
                    'source' => $rawFact['source'],
                    'embedding' => '[' . implode(',', $embedding) . ']',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param string $text
     * @return array
     */
    private function getEmbedding(string $text): array
    {
        try {
            $embResponse = HttpClient::create()->request('POST', 'http://127.0.0.1:11434/api/embeddings', [
                'json' => [
                    'model' => 'nomic-embed-text',
                    'prompt' => $text,
                ]
            ]);
            return $embResponse->toArray()['embedding'];
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return [];
        }
    }

    /**
     * @param array $rawEmbedding
     * @return bool
     */
    public function isDuplicate(array $rawEmbedding): bool
    {
        $embedding = '[' . implode(',', $rawEmbedding) . ']';
        $threshold = 0.75;

        $similarFact = Fact::selectRaw('
                *,
                (1 - (embedding <=> ?)) AS similarity
            ', [$embedding])
            ->orderByRaw('embedding <=> ?', [$embedding])
            ->whereRaw('(1 - (embedding <=> ?)) > ?', [$embedding, $threshold])
            ->limit(1)
            ->get();

        return $similarFact->count() != 0;
    }
}
