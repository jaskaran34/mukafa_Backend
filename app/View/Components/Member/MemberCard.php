<?php

namespace App\View\Components\Member;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Member;

/**
 * Class MemberCard
 *
 * Represents a member card component in a view.
 */
class MemberCard extends Component
{
    // Public property for the component
    public $member;

    /**
     * Create a new component instance.
     *
     * @param Member|null $member The member model. Default is null.
     */
    public function __construct(Member $member = null)
    {
        $this->member = $member;
    }

    /**
     * Get the view or contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.member.member-card');
    }
}
