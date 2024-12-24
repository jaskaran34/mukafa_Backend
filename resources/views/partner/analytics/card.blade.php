@extends('partner.layouts.default')

@section('page_title', $card->name . config('default.page_title_delimiter') . trans('common.analytics') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 w-full">
        <div class="mx-auto w-full">

            <div class="items-center mb-6 md:flex grid-cols-1 md:grid-cols-2">
                <div class="flex items-center">
                    <a href="{{ route('partner.analytics') }}" class="py-2.5 px-5 mr-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-full border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        <x-ui.icon icon="left" class="w-5 h-5 text-gray-900 dark:text-gray-300" />
                    </a>
                    <div>
                        <select id="range" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option value="day" @if($range == 'day') selected @endif>{{ trans('common.show_analytics_from_today') }}</option>
                            <option value="day,-1" @if($range == 'day,-1') selected @endif>{{ trans('common.show_analytics_from_yesterday') }}</option>
                            <option value="week" @if($range == 'week') selected @endif>{{ trans('common.show_analytics_from_this_week') }}</option>
                            <option value="week,-1" @if($range == 'week,-1') selected @endif>{{ trans('common.show_analytics_from_last_week') }}</option>
                            <option value="month" @if($range == 'month') selected @endif>{{ trans('common.show_analytics_from_this_month') }}</option>
                            <option value="month,-1" @if($range == 'month,-1') selected @endif>{{ trans('common.show_analytics_from_last_month') }}</option>
                            <option value="year" @if($range == 'year') selected @endif>{{ trans('common.show_analytics_from_this_year') }}</option>
                            <option value="year,-1" @if($range == 'year,-1') selected @endif>{{ trans('common.show_analytics_from_last_year') }}</option>
                        </select>
                        <script>
                            document.addEventListener('DOMContentLoaded', (event) => {
                                const rangeSelect = document.querySelector('#range');
                                rangeSelect.addEventListener('change', () => {
                                    const rangeValue = rangeSelect.value;
                                    window.location.href = window.location.pathname + '?range=' + encodeURIComponent(rangeValue);
                                });
                            });
                        </script>
                    </div>
                </div>
                <div class="grow md:ml-4 md:mt-0 mt-6">
                    @if(!$resultsFound)
                        <h5 class="text-xl font-bold dark:text-white w-full">{!! trans('common.no_results_found') !!}</h5>
                    @else
                        <h5 class="text-xl font-bold dark:text-white w-full">{!! $cardViews['label'] !!}</h5>
                    @endif
                </div>
            </div>

            <div class="space-y-8 md:grid md:grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 md:gap-8 xl:gap-8 md:space-y-0">
                <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 border border-gray-200 dark:border-gray-700">

                    <x-member.card
                        class="max-w-md mx-auto"
                        :card="$card"
                        :flippable="false"
                        :links="false"
                        :show-qr="false"
                        :auth-check="false"
                        :show-balance="false"
                    />

                    <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between mt-5">
                        <div class="flex justify-between items-center pt-5">
                            <a href="{{ route('partner.data.edit', ['name' => 'cards', 'id' => $card->id]) }}"
                                class="uppercase text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white py-2">
                                <x-ui.icon icon="arrow-top-right-on-square" class="w-4 h-4 mr-2"/>
                                {{ trans('common.edit_card') }}
                            </a>
                            @if($card->is_active)
                            <a href="{{ route('member.card', ['card_id' => $card->id]) }}" target="_blank" 
                                class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-primary-600 hover:text-primary-700 dark:hover:text-primary-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                                {{ trans('common.view_card_on_website') }}
                                <svg class="w-2.5 h-2.5 ml-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flow-root">
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li class="pb-3 sm:pb-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <x-ui.icon icon="qr-code" class="w-7 h-7 text-gray-900 dark:text-white" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ trans('common.card_views') }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ trans('common.last_view') }}: <span class="format-date">{{ ($card->last_view) ? $card->last_view->diffForHumans() : trans('common.never') }}</span>
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <span class="format-number">{{ $cardViews['total'] }}</span>
                                    </div>
                                    <div class="w-20 inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        @if ($cardViewsDifference == 0)
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-900 dark:text-gray-300">
                                                {{ $cardViewsDifference }}%
                                            </span>
                                        @elseif ($cardViewsDifference > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                                <x-ui.icon icon="arrow-chart-up" class="w-2.5 h-2.5 mr-1.5" /> {{ $cardViewsDifference }}%
                                            </span>
                                        @elseif ($cardViewsDifference < 0 && $cardViewsDifference != '-')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300">
                                                <x-ui.icon icon="arrow-chart-down" class="w-2.5 h-2.5 mr-1.5" /> {{ $cardViewsDifference }}%
                                            </span>
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <x-ui.icon icon="gift" class="w-7 h-7 text-gray-900 dark:text-white" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ trans('common.reward_views') }}
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <span class="format-number">{{ $rewardViews['total'] }}</span>
                                    </div>
                                    <div class="w-20 inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        @if ($rewardViewsDifference == 0)
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-900 dark:text-gray-300">
                                                {{ $rewardViewsDifference }}%
                                            </span>
                                        @elseif ($rewardViewsDifference > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                                <x-ui.icon icon="arrow-chart-up" class="w-2.5 h-2.5 mr-1.5" /> {{ $rewardViewsDifference }}%
                                            </span>
                                        @elseif ($rewardViewsDifference < 0 && $rewardViewsDifference != '-')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300">
                                                <x-ui.icon icon="arrow-chart-down" class="w-2.5 h-2.5 mr-1.5" /> {{ $rewardViewsDifference }}%
                                            </span>
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <x-ui.icon icon="coins" class="w-7 h-7 text-gray-900 dark:text-white" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ trans('common.points_issued') }}
                                        </p>
                                        <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                            {{ trans('common.last_points_issued') }}: <span class="format-date">{{ ($card->last_points_issued_at) ? $card->last_points_issued_at->diffForHumans() : trans('common.never') }}</span>
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <span class="format-number">{{ $pointsIssued['total'] }}</span>
                                    </div>
                                    <div class="w-20 inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        @if ($pointsIssuedDifference == 0)
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-900 dark:text-gray-300">
                                                {{ $pointsIssuedDifference }}%
                                            </span>
                                        @elseif ($pointsIssuedDifference > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                                <x-ui.icon icon="arrow-chart-up" class="w-2.5 h-2.5 mr-1.5" /> {{ $pointsIssuedDifference }}%
                                            </span>
                                        @elseif ($pointsIssuedDifference < 0 && $pointsIssuedDifference != '-')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300">
                                                <x-ui.icon icon="arrow-chart-down" class="w-2.5 h-2.5 mr-1.5" /> {{ $pointsIssuedDifference }}%
                                            </span>
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li class="py-3 sm:py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <x-ui.icon icon="building-storefront" class="w-7 h-7 text-gray-900 dark:text-white" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ trans('common.points_redeemed') }}
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <span class="format-number">{{ $pointsRedeemed['total'] }}</span>
                                    </div>
                                    <div class="w-20 inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        @if ($pointsRedeemedDifference == 0)
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-900 dark:text-gray-300">
                                                {{ $pointsRedeemedDifference }}%
                                            </span>
                                        @elseif ($pointsRedeemedDifference > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                                <x-ui.icon icon="arrow-chart-up" class="w-2.5 h-2.5 mr-1.5" /> {{ $pointsRedeemedDifference }}%
                                            </span>
                                        @elseif ($pointsRedeemedDifference < 0 && $pointsRedeemedDifference != '-')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300">
                                                <x-ui.icon icon="arrow-chart-down" class="w-2.5 h-2.5 mr-1.5" /> {{ $pointsRedeemedDifference }}%
                                            </span>
                                        @else
                                        @endif
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
                                            {{ trans('common.last_reward_claimed') }}: <span class="format-date">{{ ($card->last_reward_redeemed_at) ? $card->last_reward_redeemed_at->diffForHumans() : trans('common.never') }}</span>
                                        </p>
                                    </div>
                                    <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        <span class="format-number">{{ $rewardsClaimed['total'] }}</span>
                                    </div>
                                    <div class="w-20 inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                                        @if ($rewardsClaimedDifference == 0)
                                            <span class="bg-gray-100 text-gray-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-gray-900 dark:text-gray-300">
                                                {{ $rewardsClaimedDifference }}%
                                            </span>
                                        @elseif ($rewardsClaimedDifference > 0)
                                            <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-green-900 dark:text-green-300">
                                                <x-ui.icon icon="arrow-chart-up" class="w-2.5 h-2.5 mr-1.5" /> {{ $rewardsClaimedDifference }}%
                                            </span>
                                        @elseif ($rewardsClaimedDifference < 0 && $rewardsClaimedDifference != '-')
                                            <span class="bg-red-100 text-red-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md dark:bg-red-900 dark:text-red-300">
                                                <x-ui.icon icon="arrow-chart-down" class="w-2.5 h-2.5 mr-1.5" /> {{ $rewardsClaimedDifference }}%
                                            </span>
                                        @else
                                        @endif
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

            @if($resultsFound)

                <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 md:pb-0 pb-0 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between">
                        <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 w-1/2 mr-2">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 items-center justify-center mr-3 md:flex hidden">
                                    <x-ui.icon icon="qr-code" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                                </div>
                                <div>
                                    <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1"><span class="format-number">{{ $cardViews['total'] }}</span></h5>
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400 flex items-center"><span class="flex w-3 h-3 bg-[#1A56DB] rounded-full mr-2"></span> {{ trans('common.card_views') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 w-1/2 ml-2">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 items-center justify-center mr-3 md:flex hidden">
                                    <x-ui.icon icon="gift" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                                </div>
                                <div>
                                    <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1"><span class="format-number">{{ $rewardViews['total'] }}</span></h5>
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400 flex items-center"><span class="flex w-3 h-3 bg-[#FDBA8C] rounded-full mr-2"></span> {{ trans('common.reward_views') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="analytics-views-chart"
                        data-colors='["#1A56DB", "#FDBA8C"]'
                        data-labels='{!! '["' . implode('","', $cardViews['units']) . '"]' !!}'
                        data-label1="{{ trans('common.card_views') }}"
                        data-tooltip1="{{ trans('common.chart_tooltip_card_views') }}"
                        data-values1="{{ '[' . implode(',', $cardViews['views']) . ']' }}"
                        data-label2="{{ trans('common.reward_views') }}"
                        data-tooltip2="{{ trans('common.chart_tooltip_reward_views') }}"
                        data-values2="{{ '[' . implode(',', $rewardViews['views']) . ']' }}"
                    ></div>

                </div>

                <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6 md:pb-0 pb-0 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between">
                        <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 w-1/2 mr-2">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 items-center justify-center mr-3 md:flex hidden">
                                    <x-ui.icon icon="coins" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                                </div>
                                <div>
                                    <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1"><span class="format-number">{{ $pointsIssued['total'] }}</span></h5>
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400 flex items-center"><span class="flex w-3 h-3 bg-[#31C48D] rounded-full mr-2"></span> {{ trans('common.points_issued') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 w-1/2 ml-2">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 items-center justify-center mr-3 md:flex hidden">
                                    <x-ui.icon icon="building-storefront" class="w-6 h-6 text-gray-500 dark:text-gray-400" />
                                </div>
                                <div>
                                    <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1"><span class="format-number">{{ $pointsRedeemed['total'] }}</span></h5>
                                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400 flex items-center"><span class="flex w-3 h-3 bg-[#1C64F2] rounded-full mr-2"></span> {{ trans('common.points_redeemed') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="analytics-interactions-chart"
                        data-colors='["#31C48D", "#1C64F2"]'
                        data-labels='{!! '["' . implode('","', $pointsIssued['units']) . '"]' !!}'
                        data-label1="{{ trans('common.points_issued') }}"
                        data-tooltip1="{{ trans('common.points_issued') }}"
                        data-values1="{{ '[' . implode(',', $pointsIssued['points']) . ']' }}"
                        data-label2="{{ trans('common.points_redeemed') }}"
                        data-tooltip2="{{ trans('common.points_redeemed') }}"
                        data-values2="{{ '[' . implode(',', $pointsRedeemed['points']) . ']' }}"
                        data-label3="{{ trans('common.rewards_claimed') }}"
                        data-tooltip3="{{ trans('common.rewards_claimed') }}"
                        data-values3="{{ '[' . implode(',', $rewardsClaimed['rewards']) . ']' }}"
                    ></div>

                </div>
            @endif
        </div>
        </div>
    </div>
</div>
@stop
