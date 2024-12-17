<?php

namespace App\Http\Controllers;

use App\Models\SecretSantaParticipant;
use App\Notifications\SimpleMessageNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Orchid\Support\Facades\Toast;

class SantaController extends Controller
{
    /**
     * Отображает главную страницу Тайного Санты.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): View
    {
        $participant = $request?->user()
            ?->secretSantaParticipant()
            ->firstOrNew();

        $participant ??= new SecretSantaParticipant;

        return view('santa.index', [
            'participant' => $participant,
        ]);
    }

    /**
     * Отображает страницу с правилами участия в Тайном Санте.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function rules(): View
    {
        return view('santa.rules');
    }

    /**
     * Показывает форму для регистрации.
     */
    public function game(Request $request): View
    {
        $participant = $request->user()
            ->secretSantaParticipant()
            ->firstOrNew();

        return view('santa.game', [
            'participant' => $participant,
        ]);
    }

    /**
     * Обрабатывает заявку участника на участие в Тайном Санте.
     */
    public function update(Request $request): RedirectResponse
    {
        $participant = $request->user()
            ->secretSantaParticipant()
            ->firstOrNew();

        if (! $participant->exists) {
            Toast::warning('К сожалению, регистрация на Тайного Санту уже закрыта.')
                ->disableAutoHide();

            return redirect()->route('santa');
        }

        $data = $request->validate([
            // 'telegram'        => ['string', 'required_without:tracking_number'],
            // 'phone'           => ['string', 'required_without:tracking_number'],
            // 'address'         => ['string', 'required_without:tracking_number'],
            // 'about'           => ['string', 'required_without:tracking_number'],
            'tracking_number' => [
                'nullable',
                'string',
                Rule::requiredIf($participant->receiver_id),
            ],
        ]);

        $participant
            ->fill($data)
            ->save();

        $participant
            ->receiver
            ?->user
            ?->notify(new SimpleMessageNotification('Получатель подарка "Тайного Санты" обновил информацию. Пожалуйста, проверьте.'));

        Toast::success('Ваша заявка на участие в принята! Готовьтесь к сюрпризу.')
            ->disableAutoHide();

        return redirect()->route('santa');
    }

    /**
     * Отменяет заявку участника на участие.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $participant = $request->user()
            ->secretSantaParticipant()
            ->firstOrNew();

        $participant->delete();

        Toast::success('Ваша заявка на участие отозвана! Спасибо, что предупредили заранее.')
            ->disableAutoHide();

        return redirect()->route('santa');
    }
}
