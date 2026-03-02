<?php

namespace App\Http\Controllers;

use App\Models\Enums\FeatureStatusEnum;
use App\Models\Feature;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class FeatureController extends Controller
{
    /**
     * Display a listing of published features.
     */
    public function index(Request $request)
    {
        $query = Feature::query()->with('author');

        // For authenticated users, show their proposed features + all published
        // For guests, show only published features
        if ($user = $request->user()) {
            $query->where(function ($q) use ($user) {
                $q->where('status', FeatureStatusEnum::Published)
                    ->orWhere('status', FeatureStatusEnum::Implemented)
                    ->orWhere(function ($subQ) use ($user) {
                        $subQ->where('status', FeatureStatusEnum::Proposed)
                            ->where('user_id', $user->id);
                    });
            })
            // Sort: user's proposed features first, then by votes
                ->orderByRaw("CASE WHEN user_id = ? AND status = 'proposed' THEN 0 ELSE 1 END", [$user->id])
                ->orderBy('votes_count', 'desc')
                ->orderBy('order', 'asc');
        } else {
            $query->whereIn('status', [FeatureStatusEnum::Published, FeatureStatusEnum::Implemented])
                ->orderBy('votes_count', 'desc')
                ->orderBy('order', 'asc');
        }

        $features = $query->simplePaginate(5);

        // Attach user vote status to each feature
        if ($user = $request->user()) {
            $features->getCollection()->transform(function ($feature) use ($user) {
                $feature->user_vote = $feature->getUserVote($user);

                return $feature;
            });
        }

        $isTurboFrameRequest = $request->header('Turbo-Frame');

        if (!$isTurboFrameRequest && !$request->isMethod('POST')) {
            return view('features.index', [
                'features' => $features,
            ]);
        }

        return turbo_stream([
            turbo_stream()->removeAll('.feature-placeholder'),
            turbo_stream()->append('features-frame', view('features._list', [
                'features' => $features,
            ])),

            turbo_stream()->replace('feature-more', view('features._pagination', [
                'features' => $features,
            ])),
        ]);
    }

    public function create(Feature $feature)
    {
        return view('features.edit', [
            'feature' => $feature,
        ]);
    }

    /**
     * Store a newly created feature proposal.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Feature::class);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $feature = Feature::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'user_id'     => $request->user()->id,
        ]);

        // Load the author relationship
        $feature->load('author');

        // Add user_vote attribute
        $feature->user_vote = $feature->getUserVote($request->user());

       Toast::info('Спасибо за предложение! Оно будет рассмотрено в ближайшее время.');

        return redirect()->route('features.index');
    }

    /**
     * Vote for a feature (upvote only, no cancellation).
     */
    public function vote(Request $request, Feature $feature)
    {
        $this->authorize('vote', $feature);

        $validated = $request->validate([
            'vote' => 'required|integer|in:1',
        ]);

        $feature->toggleVote($request->user(), $validated['vote']);

        // Refresh the feature data with vote count
        $feature = $feature->fresh();
        $feature->user_vote = $feature->getUserVote($request->user());

        return turbo_stream([
            turbo_stream()
                ->target("feature-vote-{$feature->id}")
                ->action('replace')
                ->view('features._vote-button', [
                    'feature' => $feature,
                ]),
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $user = $request->user();

        // If query is empty or too short, return default list
        if (strlen($query) < 3) {
            $featureQuery = Feature::query()->with('author');

            // For authenticated users, show their proposed features + all published
            if ($user) {
                $featureQuery->where(function ($q) use ($user) {
                    $q->where('status', FeatureStatusEnum::Published)
                        ->orWhere('status', FeatureStatusEnum::Implemented)
                        ->orWhere(function ($subQ) use ($user) {
                            $subQ->where('status', FeatureStatusEnum::Proposed)
                                ->where('user_id', $user->id);
                        });
                })
                // Sort: user's proposed features first, then by votes
                    ->orderByRaw("CASE WHEN user_id = ? AND status = 'proposed' THEN 0 ELSE 1 END", [$user->id])
                    ->orderBy('votes_count', 'desc')
                    ->orderBy('order', 'asc');
            } else {
                $featureQuery->whereIn('status', [FeatureStatusEnum::Published, FeatureStatusEnum::Implemented])
                    ->orderBy('votes_count', 'desc')
                    ->orderBy('order', 'asc');
            }

            $features = $featureQuery->simplePaginate(5);
        } else {
            // Perform search using Scout
            $searchQuery = Feature::search($query);

            // For authenticated users, include their proposed features in search
            if ($user) {
                $searchQuery->query(fn ($builder) => $builder
                    ->with('author')
                    ->where(function ($q) use ($user) {
                        $q->where('status', FeatureStatusEnum::Published)
                            ->orWhere('status', FeatureStatusEnum::Implemented)
                            ->orWhere(function ($subQ) use ($user) {
                                $subQ->where('status', FeatureStatusEnum::Proposed)
                                    ->where('user_id', $user->id);
                            });
                    })
                    ->orderByRaw("CASE WHEN user_id = ? AND status = 'proposed' THEN 0 ELSE 1 END", [$user->id])
                    ->orderBy('votes_count', 'desc')
                    ->orderBy('order', 'asc')
                );
            } else {
                $searchQuery->whereIn('status', [FeatureStatusEnum::Published, FeatureStatusEnum::Implemented])
                    ->query(fn ($builder) => $builder->with('author'))
                    ->orderBy('votes_count', 'desc')
                    ->orderBy('order', 'asc');
            }

            $features = $searchQuery->simplePaginate(5);
        }

        if ($user) {
            $features->getCollection()->transform(function ($feature) use ($user) {
                $feature->user_vote = $feature->getUserVote($user);

                return $feature;
            });
        }

        return turbo_stream([
            turbo_stream()
                ->target('features-frame')
                ->action('replace')
                ->view('features._search-results', [
                    'features' => $features,
                ]),
            turbo_stream()
                ->target('feature-more')
                ->action('replace')
                ->view('features._pagination', [
                    'features' => $features,
                ]),
        ]);
    }
}
