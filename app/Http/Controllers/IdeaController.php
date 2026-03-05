<?php

namespace App\Http\Controllers;

use App\Models\Enums\IdeaStatusEnum;
use App\Models\Idea;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $query = Idea::query()->with('author');

        if ($user = $request->user()) {
            $query->where(function ($q) use ($user) {
                $q->where('status', IdeaStatusEnum::Published)
                    ->orWhere('status', IdeaStatusEnum::Implemented)
                    ->orWhere(function ($subQ) use ($user) {
                        $subQ->where('status', IdeaStatusEnum::Proposed)
                            ->where('user_id', $user->id);
                    });
            })
                ->orderByRaw("CASE WHEN user_id = ? AND status = 'proposed' THEN 0 ELSE 1 END", [$user->id])
                ->orderBy('votes_count', 'desc');
        } else {
            $query->whereIn('status', [IdeaStatusEnum::Published, IdeaStatusEnum::Implemented])
                ->orderBy('votes_count', 'desc');
        }

        $ideas = $query->simplePaginate(5);

        if ($user = $request->user()) {
            $ideas->getCollection()->transform(function ($idea) use ($user) {
                $idea->user_vote = $idea->getUserVote($user);

                return $idea;
            });
        }

        $isTurboFrameRequest = $request->header('Turbo-Frame');

        if (! $isTurboFrameRequest && ! $request->isMethod('POST')) {
            return view('ideas.index', [
                'ideas' => $ideas,
            ]);
        }

        return turbo_stream([
            turbo_stream()->removeAll('.idea-placeholder'),
            turbo_stream()->append('ideas-frame', view('ideas._list', [
                'ideas' => $ideas,
            ])),
            turbo_stream()->replace('idea-more', view('ideas._pagination', [
                'ideas' => $ideas,
            ])),
        ]);
    }

    public function create()
    {
        return view('ideas.edit', [
            'idea' => new Idea,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Idea::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $idea = Idea::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'user_id'     => $request->user()->id,
        ]);

        $idea->load('author');
        $idea->user_vote = $idea->getUserVote($request->user());

        Toast::info('Спасибо за идею! Она будет рассмотрена в ближайшее время.');

        return redirect()->route('ideas.index');
    }

    public function vote(Request $request, Idea $idea)
    {
        $this->authorize('vote', $idea);

        $validated = $request->validate([
            'vote' => 'required|integer|in:1',
        ]);

        $idea->toggleVote($request->user(), $validated['vote']);

        $idea = $idea->fresh();
        $idea->user_vote = $idea->getUserVote($request->user());

        return turbo_stream([
            turbo_stream()
                ->target("idea-vote-{$idea->id}")
                ->action('replace')
                ->view('ideas._vote-button', [
                    'idea' => $idea,
                ]),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $user = $request->user();

        if (strlen($query) < 3) {
            $ideaQuery = Idea::query()->with('author');

            if ($user) {
                $ideaQuery->where(function ($q) use ($user) {
                    $q->where('status', IdeaStatusEnum::Published)
                        ->orWhere('status', IdeaStatusEnum::Implemented)
                        ->orWhere(function ($subQ) use ($user) {
                            $subQ->where('status', IdeaStatusEnum::Proposed)
                                ->where('user_id', $user->id);
                        });
                })
                    ->orderByRaw("CASE WHEN user_id = ? AND status = 'proposed' THEN 0 ELSE 1 END", [$user->id])
                    ->orderBy('votes_count', 'desc');
            } else {
                $ideaQuery->whereIn('status', [IdeaStatusEnum::Published, IdeaStatusEnum::Implemented])
                    ->orderBy('votes_count', 'desc');
            }

            $ideas = $ideaQuery->simplePaginate(5);
        } else {
            $searchQuery = Idea::search($query);

            if ($user) {
                $searchQuery->query(fn ($builder) => $builder
                    ->with('author')
                    ->where(function ($q) use ($user) {
                        $q->where('status', IdeaStatusEnum::Published)
                            ->orWhere('status', IdeaStatusEnum::Implemented)
                            ->orWhere(function ($subQ) use ($user) {
                                $subQ->where('status', IdeaStatusEnum::Proposed)
                                    ->where('user_id', $user->id);
                            });
                    })
                    ->orderByRaw("CASE WHEN user_id = ? AND status = 'proposed' THEN 0 ELSE 1 END", [$user->id])
                    ->orderBy('votes_count', 'desc')
                );
            } else {
                $searchQuery->whereIn('status', [IdeaStatusEnum::Published, IdeaStatusEnum::Implemented])
                    ->query(fn ($builder) => $builder->with('author'))
                    ->orderBy('votes_count', 'desc');
            }

            $ideas = $searchQuery->simplePaginate(5);
        }

        if ($user) {
            $ideas->getCollection()->transform(function ($idea) use ($user) {
                $idea->user_vote = $idea->getUserVote($user);

                return $idea;
            });
        }

        return turbo_stream([
            turbo_stream()
                ->target('ideas-frame')
                ->action('replace')
                ->view('ideas._search-results', [
                    'ideas' => $ideas,
                ]),
            turbo_stream()
                ->target('idea-more')
                ->action('replace')
                ->view('ideas._pagination', [
                    'ideas' => $ideas,
                ]),
        ]);
    }
}
