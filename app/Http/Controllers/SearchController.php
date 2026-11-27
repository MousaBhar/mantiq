<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request, SearchService $search): View
    {
        $q = trim((string) $request->query('q', ''));

        return view('search.index', [
            'q' => $q,
            'results' => $q !== '' ? $search->search(mb_substr($q, 0, 80), 12) : null,
        ]);
    }
}
