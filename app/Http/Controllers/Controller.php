<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function normalizeSearch(?string $search): string
    {
        return preg_replace('/\s+/u', ' ', trim((string) $search)) ?? '';
    }

    protected function searchTokens(string $search): array
    {
        $tokens = preg_split('/[^\pL\pN]+/u', mb_strtolower($search), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return $tokens !== [] || $search === '' ? $tokens : [mb_strtolower($search)];
    }

    protected function searchLikePattern(string $token): string
    {
        return '%' . addcslashes($token, '\\%_') . '%';
    }
}
