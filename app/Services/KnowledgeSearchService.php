<?php

namespace App\Services;

use App\Models\Knowledge;
use Illuminate\Support\Collection;

class KnowledgeSearchService
{
    /**
     * 質問文をキーワードとして公開ナレッジを検索し、マッチしたナレッジを関連度順で返す。
     */
    public function search(string $query, ?string $context = null): Collection
    {
        $searchText = trim($query);
        if ($context) {
            $searchText = trim($context . ' ' . $query);
        }
        if ($searchText === '') {
            return collect();
        }

        $keywords = $this->extractKeywords($searchText)->filter(fn ($w) => mb_strlen($w) >= 2)->values();
        if ($keywords->isEmpty()) {
            return collect();
        }

        $builder = Knowledge::published()
            ->with(['category', 'tags']);

        $builder->where(function ($q) use ($keywords) {
            foreach ($keywords as $kw) {
                if (mb_strlen($kw) < 2) {
                    continue;
                }
                $q->orWhere(function ($q2) use ($kw) {
                    $q2->where('word', 'like', '%' . $kw . '%')
                        ->orWhere('detail', 'like', '%' . $kw . '%');
                });
            }
        });

        $results = $builder->get();

        return $this->rankByRelevance($results, $keywords);
    }

    private function extractKeywords(string $text): Collection
    {
        $text = mb_convert_kana($text, 's', 'UTF-8');
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        return collect($words)->filter(fn ($w) => mb_strlen($w) >= 1)->unique()->values();
    }

    private function rankByRelevance(Collection $knowledgeList, Collection $keywords): Collection
    {
        return $knowledgeList->map(function ($k) use ($keywords) {
            $score = 0;
            $word = $k->word ?? '';
            $detail = $k->detail ?? '';
            foreach ($keywords as $kw) {
                if (mb_strlen($kw) < 2) {
                    continue;
                }
                if (mb_strpos($word, $kw) !== false) {
                    $score += 3;
                }
                $score += substr_count(mb_strtolower($detail), mb_strtolower($kw));
            }
            $k->relevance_score = $score;
            return $k;
        })->sortByDesc('relevance_score')->values();
    }
}
