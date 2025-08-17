<?php

namespace App\Http\Controllers;

use App\Models\IdeaKey;
use App\Models\IdeaRequest;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class IdeaController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|null
     */
    public function index()
    {
        return view('idea.index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        Toast::success('Laravel Idea с 30 июля 2025 года бесплатен для всех пользователей PhpStorm.')
            ->disableAutoHide();

        return redirect()->route('home');
    }

    /**
     * @param \App\Models\IdeaKey $key
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function key(IdeaKey $key)
    {
        return view('idea.key', [
            'key' => $key,
        ]);
    }
}
