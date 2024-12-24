@extends('partner.layouts.default')

@section('page_title', trans('common.analytics') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 w-full">
        <div class="mx-auto w-full">
            <div class="flex items-center mb-6">
                <div>
                    <select id="sort" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        @php
                            $sortOptions = [
                                'views,desc' => trans('common.sort_by_most_viewed'),
                                'views,asc' => trans('common.sort_by_least_viewed'),
                                'last_view,desc' => trans('common.sort_by_most_recently_viewed'),
                                'last_view,asc' => trans('common.sort_by_least_recently_viewed'),
                                'total_amount_purchased,desc' => trans('common.sort_by_highest_revenue'),
                                'total_amount_purchased,asc' => trans('common.sort_by_lowest_revenue'),
                                'number_of_points_issued,desc' => trans('common.sort_by_most_points_issued'),
                                'number_of_points_issued,asc' => trans('common.sort_by_fewest_points_issued'),
                                'number_of_points_redeemed,desc' => trans('common.sort_by_most_points_redeemed'),
                                'number_of_points_redeemed,asc' => trans('common.sort_by_fewest_points_redeemed'),
                                'number_of_rewards_redeemed,desc' => trans('common.sort_by_most_rewards_claimed'),
                                'number_of_rewards_redeemed,asc' => trans('common.sort_by_fewest_rewards_claimed'),
                                'last_points_issued_at,desc' => trans('common.sort_by_most_recently_issued_points'),
                                'last_points_issued_at,asc' => trans('common.sort_by_least_recently_issued_points'),
                                'last_reward_redeemed_at,desc' => trans('common.sort_by_most_recently_claimed_reward'),
                                'last_reward_redeemed_at,asc' => trans('common.sort_by_least_recently_claimed_reward')
                            ];
                        @endphp
                        @foreach($sortOptions as $value => $label)
                            <option value="{{ $value }}" @if($sort == $value) selected @endif>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center pl-4">
                    <input id="active_only" type="checkbox" value="true" @if($active_only == 'true') checked @endif name="active_only" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="active_only" class="w-full py-2 ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ trans('common.only_show_active_cards') }}</label>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', (event) => {
                    const sortSelect = document.querySelector('#sort');
                    const activeOnlyCheckbox = document.querySelector('#active_only');
                
                    sortSelect.addEventListener('change', reloadWithQueryString);
                    activeOnlyCheckbox.addEventListener('change', reloadWithQueryString);
                
                    function reloadWithQueryString() {
                        const sortValue = sortSelect.value;
                        const activeOnlyValue = activeOnlyCheckbox.checked ? 'true' : 'false';
                
                        window.location.href = window.location.pathname + '?sort=' + encodeURIComponent(sortValue) + '&active_only=' + encodeURIComponent(activeOnlyValue);
                    }
                });
            </script>

            <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 md:gap-8 xl:gap-8 md:space-y-0">
                @foreach($cards as $card)
                    <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 border border-gray-200 dark:border-gray-700">
                        <x-member.card
                            :card="$card"
                            :flippable="false"
                            :links="false"
                            :show-qr="false"
                            :auth-check="false"
                            :show-balance="false"
                            :custom-link="route('partner.analytics.card', ['card_id' => $card->id])"
                        />
                        <div class="flow-root mt-3">
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-ui.icon icon="funnel" class="w-7 h-7 text-gray-900 dark:text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $card->name }}</p>
                                            <p class="text-gray-500 dark:text-gray-400 mt-1">
                                                <span class="{{ ($card->is_active) ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }} text-xs font-medium mr-1 px-2.5 py-1 rounded">{{ ($card->is_active) ? trans('common.active') : trans('common.deactivated') }}</span>
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-1 px-2.5 py-1 rounded dark:bg-gray-700 dark:text-gray-300">{{ $card->club->name }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-ui.icon icon="eye" class="w-7 h-7 text-gray-900 dark:text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ trans('common.views') }}</p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ trans('common.last_view') }}: <span class="format-date">{{ ($card->last_view) ? $card->last_view->diffForHumans() : trans('common.never') }}</span></p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <span class="format-number">{{ $card->views }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-ui.icon icon="banknotes" class="w-7 h-7 text-gray-900 dark:text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ trans('common.total_purchased') }}</p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <span class="format-number">{{ $card->parseMoney($card->total_amount_purchased) }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-ui.icon icon="coins" class="w-7 h-7 text-gray-900 dark:text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ trans('common.points_issued') }}</p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ trans('common.last_points_issued') }}: <span class="format-date">{{ ($card->last_points_issued_at) ? $card->last_points_issued_at->diffForHumans() : trans('common.never') }}</span></p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <span class="format-number">{{ $card->number_of_points_issued }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="py-3 sm:py-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-ui.icon icon="building-storefront" class="w-7 h-7 text-gray-900 dark:text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ trans('common.points_redeemed') }}</p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <span class="format-number">{{ $card->number_of_points_redeemed }}</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="pt-3 sm:pt-4">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <x-ui.icon icon="trophy" class="w-7 h-7 text-gray-900 dark:text-white" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">{{ trans('common.rewards_claimed') }}</p>
                                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">{{ trans('common.last_reward_claimed') }}: <span class="format-date">{{ ($card->last_reward_redeemed_at) ? $card->last_reward_redeemed_at->diffForHumans() : trans('common.never') }}</span></p>
                                        </div>
                                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                            <span class="format-number">{{ $card->number_of_rewards_redeemed }}</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between mt-5">
                            <div class="flex justify-between items-center pt-5">
                                <a href="{{ route('partner.data.edit', ['name' => 'cards', 'id' => $card->id]) }}" class="uppercase text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white py-2">
                                    <x-ui.icon icon="arrow-top-right-on-square" class="w-4 h-4 mr-2"/>
                                    {{ trans('common.edit_card') }}
                                </a>
                                @if($card->is_active)
                                    <a href="{{ route('member.card', ['card_id' => $card->id]) }}" target="_blank" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-primary-600 hover:text-primary-700 dark:hover:text-primary-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                                        {{ trans('common.view_card_on_website') }}
                                        <svg class="w-2.5 h-2.5 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
