@extends('member.layouts.default')

@section('page_title', trans('common.wallet') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 w-full">
        <div class="mx-auto w-full">

            <div class="flex flex-wrap items-center mb-6">
                <div class="mr-4">
                    <select id="sort" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option value="last_points_claimed_at,desc" @if($sort == 'last_points_claimed_at,desc') selected @endif>{{ trans('common.sort_by_most_recently_issued_points') }}</option>
                        <option value="last_points_claimed_at,asc" @if($sort == 'last_points_claimed_at,asc') selected @endif>{{ trans('common.sort_by_least_recently_issued_points') }}</option>
                        <option value="total_amount_purchased,desc" @if($sort == 'total_amount_purchased,desc') selected @endif>{{ trans('common.sort_by_highest_total_purchase_amount') }}</option>
                        <option value="total_amount_purchased,asc" @if($sort == 'total_amount_purchased,asc') selected @endif>{{ trans('common.sort_by_lowest_total_purchase_amount') }}</option>
                        <option value="number_of_rewards_claimed,desc" @if($sort == 'number_of_rewards_claimed,desc') selected @endif>{{ trans('common.sort_by_most_rewards_claimed') }}</option>
                        <option value="number_of_rewards_claimed,asc" @if($sort == 'number_of_rewards_claimed,asc') selected @endif>{{ trans('common.sort_by_fewest_rewards_claimed') }}</option>
                        <option value="last_reward_claimed_at,desc" @if($sort == 'last_reward_claimed_at,desc') selected @endif>{{ trans('common.sort_by_most_recently_claimed_reward') }}</option>
                        <option value="last_reward_claimed_at,asc" @if($sort == 'last_reward_claimed_at,asc') selected @endif>{{ trans('common.sort_by_least_recently_claimed_reward') }}</option>
                    </select>
                </div>
                <div>
                    <div class="flex items-center">
                        <input id="hide_expired" type="checkbox" value="true" @if($hide_expired == 'true') checked @endif name="hide_expired" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="hide_expired" class="w-full py-2 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ trans('common.hide_expired_cards') }}</label>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const sortSelect = document.querySelector('#sort');
                    const hideExpiredCheckbox = document.querySelector('#hide_expired');

                    if (sortSelect) {
                        sortSelect.addEventListener('change', reloadWithQueryString);
                    }

                    if (hideExpiredCheckbox) {
                        hideExpiredCheckbox.addEventListener('change', reloadWithQueryString);
                    }

                    function reloadWithQueryString() {
                        const sortValue = sortSelect ? sortSelect.value : '';
                        const hideExpiredValue = hideExpiredCheckbox ? (hideExpiredCheckbox.checked ? 'true' : 'false') : '';

                        let queryString = '?';
                        if (sortValue) {
                            queryString += 'sort=' + encodeURIComponent(sortValue);
                        }
                        if (hideExpiredValue) {
                            if (queryString !== '?') {
                                queryString += '&';
                            }
                            queryString += 'hide_expired=' + encodeURIComponent(hideExpiredValue);
                        }

                        window.location.href = window.location.pathname + queryString;
                    }
                });
            </script>

            @if($cards->count() > 0)
                <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 md:gap-8 xl:gap-8 md:space-y-0">
                    @foreach($cards as $card)
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                            <x-member.card
                                :card="$card"
                                :flippable="false"
                                :links="false"
                                :show-qr="true"
                                :auth-check="false"
                                :show-balance="true"
                                :custom-link="route('member.card', ['card_id' => $card->id])"
                            />
                            <div class="flow-root mt-3">
                                <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <li class="py-3 sm:py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <x-ui.icon icon="banknotes" class="w-7 h-7 text-gray-900 dark:text-white" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    {{ trans('common.total_purchased') }}
                                                </p>
                                            </div>
                                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                                <span class="format-number">{{ $card->parseMoney($card->total_amount_purchased_by_member) }}</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="pt-3 sm:pt-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                <x-ui.icon icon="trophy" class="w-7 h-7 text-gray-900 dark:text-white" />
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                                    {{ trans('common.rewards_claimed') }}
                                                </p>
                                                <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                                    {{ trans('common.last_reward_claimed') }}: <span class="format-date">{{ $card->last_reward_claimed_by_member_at ? $card->last_reward_claimed_by_member_at->diffForHumans() : trans('common.never') }}</span>
                                                </p>
                                            </div>
                                            <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                                <span class="format-number">{{ $card->number_of_rewards_claimed_by_member }}</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="space-y-3 h-full w-full place-items-center">
                    <div class="grid space-y-3 h-full w-full place-items-center text-gray-800 dark:text-gray-200">
                        <div class="w-80 my-20">
                            <x-ui.icon icon="wallet" class="h-40 w-40 mx-auto" />
                            <div class="mt-3 text-center text-2xl font-semibold">
                                {{ trans('common.no_cards_found') }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@stop
