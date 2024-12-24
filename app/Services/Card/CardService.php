<?php

namespace App\Services\Card;

use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\Card;
use App\Models\Transaction;

class CardService
{
    /**
     * Retrieve a Card by its ID.
     *
     * @param int $id The ID of the card to find.
     * @param bool $authUserIsOwner (Optional) If true, checks if the authenticated user is the owner of the card.
     * @param string $guardUserIsOwner (Optional) The guard of the authenticated user.
     *
     * @return Card|null The found Card object if any, otherwise null.
     */
    public function findCard(int $id, bool $authUserIsOwner = false, string $guardUserIsOwner = 'partner'): ?Card
    {
        // Build the base query
        $query = Card::where('id', $id);

        // Add the owner constraint if needed
        if ($authUserIsOwner) {
            $query->where('created_by', auth($guardUserIsOwner)->user()->id);
        }

        // Execute the query and return the result
        return $query->first();
    }

    /**
     * Retrieve an active Card by its ID.
     *
     * @param int $id The ID of the card to find.
     * @param bool $authUserIsOwner (Optional) If true, checks if the authenticated user is the owner of the card.
     * @param string $guardUserIsOwner (Optional) The guard of the authenticated user.
     * @param bool $hideColumnsForPublic (Optional) Determines whether to hide columns for public use.
     *
     * @return Card|null The found Card object if any, otherwise null.
     */
    public function findActiveCard(int $id, bool $authUserIsOwner = false, string $guardUserIsOwner = 'partner', bool $hideColumnsForPublic = false): ?Card
    {
        // Build the base query
        $query = Card::where('id', $id)
            ->where('is_active', true)
            ->whereHas('club', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('is_active', true);
            });

        // Add the owner constraint if needed
        if ($authUserIsOwner) {
            $query->where('created_by', auth($guardUserIsOwner)->user()->id);
        }

        // Execute the query and get the card
        $card = $query->first();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic && $card) {
            $card->hideForPublic();
        }

        // Return the card
        return $card;
    }

    /**
     * Retrieve an active Card by its unique identifier.
     * 
     * Note: This function does not check for card issue date or expiration date. 
     * This is because partners and staff should still be able to see the cards in the dashboard 
     * even if they're outside of their issue and expiration dates.
     *
     * @param string $unique_identifier The ID of the card to find.
     *
     * @return Card|null The found Card object if any, otherwise null.
     */
    public function findActiveCardByIdentifier(string $unique_identifier): ?Card
    {
        // Query for an active card with the given unique identifier and an active club
        return Card::where('unique_identifier', $unique_identifier)
            ->where('is_active', true)
            ->whereHas('club', function ($query) {
                $query->where('is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('is_active', true);
            })
            ->first();
    }

    /**
     * Retrieve all cards associated with a specific partner.
     *
     * @param int $partner_id ID of the partner to get cards from.
     * @param string $orderBy Field name to sort by (default is 'views').
     * @param string $orderByDirection Sorting direction (default is 'desc').
     * @param string|null $where Optional additional condition field (default is 'is_active').
     * @param mixed|null $whereValue Optional value for the additional condition (default is true).
     * @param bool $hideColumnsForPublic Determines whether to hide columns for public use.
     * @return Collection A collection of Card objects found, empty collection if no cards are found.
     */
    public function findCardsFromPartner(int $partner_id, string $orderBy = 'views', string $orderByDirection = 'desc', ?string $where = 'is_active', $whereValue = true, bool $hideColumnsForPublic = false): Collection
    {
        // Construct a query to select cards where the 'created_by' matches the provided partner id.
        // The orderBy method is used to sort the cards based on 'views' (or any other specified field) in descending order (or specified order).
        $query = Card::where('created_by', $partner_id)
                    ->orderBy($orderBy, $orderByDirection);

        // If additional condition is provided, apply it to the query
        if ($where) {
            $query->where($where, $whereValue);
        }

        // Execute the query and get the result as a collection of Card objects.
        $cards = $query->get();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }

        // Return the cards
        return $cards;
    }

    /**
     * Retrieve all active cards associated with a specific partner.
     *
     * @param int $partner_id ID of the partner to get cards from.
     * @param bool $hideColumnsForPublic Determines whether to hide columns for public use.
     * @return Collection A collection of Card objects found, empty collection if no cards are found.
     */
    public function findActiveCardsFromPartner(int $partner_id, bool $hideColumnsForPublic = false): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');

        // Build the base query
        $query = Card::where('is_active', true)
            ->where('is_visible_by_default', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query and get the result as a collection of Card objects.
        $cards = $query->get();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }

        // Return the cards
        return $cards;
    }

    /**
     * Retrieve all active cards that are visible by default (`cards.is_visible_by_default`).
     *
     * @return Collection The collection of found Card objects if any, otherwise an empty collection.
     */
    public function findActiveCardsVisibleByDefault(): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');

        // Build the base query
        $query = Card::where('is_active', true)
            ->where('is_visible_by_default', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query and return the results
        return $query->get();
    }

    /**
     * Retrieve all active cards that are visible when a member is logged in (`cards.is_visible_when_logged_in`).
     *
     * @return Collection The collection of found Card objects if any, otherwise an empty collection.
     */
    public function findActiveCardsVisibleWhenLoggedIn(): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');

        // Build the base query
        $query = Card::where('is_active', true)
            ->where('is_visible_when_logged_in', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query and return the results
        return $query->get();
    }

    /**
     * Retrieve all active cards followed by a member.
     * 
     * @param int $member_id The ID of member.
     * @param bool $hideColumnsForPublic Determines whether to hide columns for public use.
     *
     * @return Collection The collection of found Card objects if any, otherwise an empty collection.
     */
    public function findActiveCardsFollowedByMember(int $member_id, bool $hideColumnsForPublic = false): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');

        // Build the query
        $query = Card::where('is_active', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->whereHas('members', function ($query) use ($member_id) {
                $query->where('members.id', $member_id);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query
        $cards = $query->get();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }

        // Return the cards
        return $cards;
    }

    /**
     * Retrieve all active cards where a member has transactions.
     * 
     * @param int $member_id The ID of member.
     * @param bool $hideColumnsForPublic Determines whether to hide columns for public use.
     *
     * @return Collection The collection of found Card objects if any, otherwise an empty collection.
     */
    public function findActiveCardsWithMemberTransactions(int $member_id, bool $hideColumnsForPublic = false): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');
    
        // Build the query
        $query = Card::where('is_active', true)
            ->where('issue_date', '<=', $now)
            ->where('expiration_date', '>', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->whereHas('transactions.member', function ($query) use ($member_id) {
                $query->where('members.id', $member_id);
            })
            ->orderBy('issue_date', 'desc');

        // Execute the query
        $cards = $query->get();

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }

        // Return the cards
        return $cards;
    }

    /**
     * Retrieve all (active and inactive) cards where a member has transactions.
     * 
     * @param int $member_id The ID of member.
     * @param string $orderBy Field name to sort by (default is 'views').
     * @param string $orderByDirection Sorting direction (default is 'desc').
     * @param string|null $where Optional additional condition field (default is 'is_active').
     * @param mixed|null $whereValue Optional value for the additional condition (default is true).
     * @param bool $hideExpired Determines whether to hide expired cards.
     * @param bool $hideColumnsForPublic Determines whether to hide columns for public use.
     *
     * @return Collection The collection of found Card objects if any, otherwise an empty collection.
     */
    public function findCardsWithMemberTransactions(int $member_id, string $orderBy = 'views', string $orderByDirection = 'desc', ?string $where = 'is_active', $whereValue = true, bool $hideExpired = false, bool $hideColumnsForPublic = false): Collection
    {
        // Get the current time in UTC
        $now = Carbon::now('UTC');

        // Define subqueries for custom attributes
        $lastPointsIssuedSubquery = Transaction::select('created_at')
            ->whereColumn('card_id', 'cards.id')
            ->where('member_id', $member_id)
            ->whereIn('event', ['initial_bonus_points', 'staff_credited_points_for_purchase'])
            ->latest('created_at')
            ->limit(1);

        $totalAmountPurchasedSubquery = Transaction::selectRaw('SUM(purchase_amount) as total_amount_purchased')
            ->whereColumn('card_id', 'cards.id')
            ->where('member_id', $member_id);

        $numberOfRewardsClaimedSubquery = Transaction::selectRaw('COUNT(*)')
            ->whereColumn('card_id', 'cards.id')
            ->where('member_id', $member_id)
            ->where('event', 'staff_redeemed_points_for_reward');

        $lastRewardClaimedSubquery = Transaction::select('created_at')
            ->whereColumn('card_id', 'cards.id')
            ->where('member_id', $member_id)
            ->where('event', 'staff_redeemed_points_for_reward')
            ->latest('created_at')
            ->limit(1);

        // Build the query
        $query = Card::where('issue_date', '<=', $now)
            ->whereHas('club', function ($query) {
                $query->where('clubs.is_active', true);
            })
            ->whereHas('partner', function ($query) {
                $query->where('partners.is_active', true);
            })
            ->whereHas('transactions.member', function ($query) use ($member_id) {
                $query->where('members.id', $member_id);
            });

        // Apply additional condition if provided
        if ($where) {
            $query->where($where, $whereValue);
        }

        // Add select statements for custom attributes
        $query->addSelect([
            'cards.*',
            'last_points_claimed_at' => $lastPointsIssuedSubquery,
            'total_amount_purchased' => $totalAmountPurchasedSubquery,
            'number_of_rewards_claimed' => $numberOfRewardsClaimedSubquery,
            'last_reward_claimed_at' => $lastRewardClaimedSubquery,
        ]);

        // Execute the query and get the results
        $cards = $query->get();

        // Add the 'expired' property to each card
        foreach ($cards as $card) {
            $card->expired = $card->expiration_date <= $now;
        }

        // Filter out expired cards if hideExpired is true
        if ($hideExpired) {
            $cards = $cards->filter(function ($card) {
                return !$card->expired;
            });
        }

        // Sort the cards in PHP
        if (in_array($orderBy, [
            'last_points_claimed_at',
            'total_amount_purchased',
            'number_of_rewards_claimed',
            'last_reward_claimed_at'
        ])) {
            $cards = $cards->sortBy(function ($card) use ($orderBy) {
                return $card->{$orderBy};
            }, SORT_REGULAR, $orderByDirection === 'desc');
        }

        // If $hideColumnsForPublic is true, hide the columns for public
        if ($hideColumnsForPublic) {
            $cards->each(function ($card) {
                $card->hideForPublic();
            });
        }

        // Return the sorted cards
        return $cards->values();
    }

    /**
     * Associate the authenticated member with the provided card.
     * 
     * @param  Card|null  $card
     * @return bool
     */
    public function followCard(Card $card = null): bool
    {
        // Check if card is not null
        if ($card === null) {
            return false;
        }
        
        // Retrieve the authenticated member
        $member = auth('member')->user();

        // Attach the member to the card without detaching existing members
        $card->members()->syncWithoutDetaching([$member->id]);

        return true;
    }

    /**
     * Disassociate the authenticated member from the provided card.
     * 
     * @param  Card|null  $card
     * @return bool
     */
    public function unfollowCard(Card $card = null): bool
    {
        // If no card is provided, return false immediately.
        if ($card === null) {
            return false;
        }
        
        // Get the currently authenticated member.
        $member = auth('member')->user();

        // Disassociate the member from the card.
        $card->members()->detach($member->id);

        return true;
    }
}