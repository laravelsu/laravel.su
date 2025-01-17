<?php

namespace App\Console\Commands;

use App\Achievements\Events\SecretSanta;
use App\Models\SecretSantaParticipant;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class UpdateAchievementsForSanta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-achievements-santa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Award achievements to Secret Santa participants who have completed their status.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Starting the achievement update process for Secret Santa participants.');

        $participants = $this->getEligibleParticipants();

        $this->rewardParticipants($participants);

        $this->info('Achievement update process completed.');
    }

    /**
     * Retrieve participants eligible for the Secret Santa achievement.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getEligibleParticipants(): Collection
    {
        return SecretSantaParticipant::with('user')
            ->where('status', 'done')
            ->get()
            ->whenEmpty(function () {
                $this->info('No eligible participants found.');

                return collect();
            });
    }

    /**
     * Reward the eligible participants with the Secret Santa achievement.
     *
     * @param \Illuminate\Support\Collection<SecretSantaParticipant> $participants
     */
    private function rewardParticipants(Collection $participants): void
    {
        $participants->each(function (SecretSantaParticipant $participant) {
            $user = $participant->user;

            if ($user === null) {
                $this->warn("Participant ID {$participant->id} does not have an associated user.");
            }

            $user->reward(SecretSanta::class);
            $this->info("Awarded Secret Santa achievement to user: {$user->id}");
        });
    }
}
