<?php

namespace App\View\Components\Member;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Card;

/**
 * Class FollowCard
 *
 * Represents a follow card component in a view.
 */
class FollowCard extends Component
{
    // Public properties for the component
    public $card;
    public $follows;
    public $canFollow;

    /**
     * Create a new component instance.
     *
     * @param Card|null $card The card model.
     * @param bool $follows Indicates if the logged-in member follows this card.
     */
    public function __construct(Card $card = null, bool $follows = false)
    {
        $this->card = $card;
        $this->follows = $follows;
        $this->canFollow = $card ? !$card->is_visible_by_default : false;

        if (auth('member')->check() && $card) {
            if ($card->getMemberBalance(auth('member')->user()) > 0) {
                $this->canFollow = false;
            }
            $this->follows = $card->members()->where('members.id', auth('member')->user()->id)->exists();
        }
    }

    /**
     * Get the view or contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.member.follow-card');
    }
}
