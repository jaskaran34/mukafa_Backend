@extends('staff.layouts.default')

@section('page_title', $card->head . config('default.page_title_delimiter') . trans('common.add_points_to_balance') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<div class="flex flex-col w-full p-6">
    <div class="space-y-6 h-full w-full place-items-center">
        <div class="max-w-md mx-auto">
            @if($member && $card)
                <x-forms.messages />
                <x-forms.form-open action="{{ route('staff.earn.points.post', ['member_identifier' => $member->unique_identifier, 'card_identifier' => $card->unique_identifier]) }}" enctype="multipart/form-data" method="POST" />
                    <div class="grid gap-4 sm:col-span-2 md:gap-6 sm:grid-cols-2 mb-6">
                        <div>
                            <x-forms.input
                                name="purchase_amount"
                                value=""
                                :label="trans('common.purchase_amount')"
                                type="number"
                                inputmode="decimal"
                                :prefix="$currency['symbol_native']"
                                :suffix="$card->currency"
                                affix-class="text-gray-400 dark:text-gray-500 text-xl"
                                input-class="text-xl"
                                :min="0"
                                :step="$currency['step']"
                                :placeholder="$currency['placeholder']"
                                :required="true"
                                class="text-gray-400"
                            />
                            <label class="relative inline-flex items-center mt-3 cursor-pointer">
                                <input type="hidden" value="0" name="points_only">
                                <input type="checkbox" value="1" name="points_only" id="points_only" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ trans('common.enter_points_only') }}</span>
                            </label>
                        </div>
                        <div>
                            <x-forms.input
                                name="points"
                                value=""
                                inputmode="numeric"
                                :label="trans('common.points')"
                                type="number"
                                icon="coins"
                                affix-class="text-gray-400 dark:text-gray-500 text-xl"
                                input-class="text-xl cursor-not-allowed"
                                :min="$card->min_points_per_purchase"
                                :max="$card->max_points_per_purchase"
                                step="1"
                                placeholder="0"
                                :required="false"
                                :readonly="true"
                            />
                        </div>
                    </div>

                    <div class="grid gap-4 sm:col-span-2 md:gap-6 sm:grid-cols-1 mb-6">
                        <x-forms.image
                            type="image"
                            capture="environment"
                            icon="camera"
                            name="image"
                            :placeholder="trans('common.add_photo_of_receipt')"
                            accept="image/*"
                        />
                    </div>

                    <div class="grid gap-4 sm:col-span-2 md:gap-6 sm:grid-cols-1 mb-6">
                        <x-forms.input
                            name="note"
                            value=""
                            type="text"
                            input-class="text-xl"
                            :placeholder="trans('common.optional_note')"
                            :required="false"
                        />
                    </div>

                    <div class="mb-6">
                        <button type="submit" class="btn-primary btn-lg w-full h-16">{{ trans('common.add_points_to_balance') }}</button>
                    </div>
                <x-forms.form-close />

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const purchaseAmountInput = document.querySelector('[name=purchase_amount]');
                        const purchaseAmountLabel = document.querySelector('label[for=purchase_amount]');
                        const purchaseAmountPrefix = document.getElementById('purchase_amount_prefix');
                        const purchaseAmountSuffix = document.getElementById('purchase_amount_suffix');
                        const pointsInput = document.querySelector('[name=points]');
                        const pointsLabel = document.querySelector('label[for=points]');
                        const pointsOnly = document.getElementById('points_only');

                        function updateClasses(element, addClasses, removeClasses) {
                            element.classList.add(...addClasses);
                            element.classList.remove(...removeClasses);
                        }

                        pointsOnly.addEventListener('change', function() {
                            if (this.checked) {
                                updateClasses(purchaseAmountInput, ['cursor-not-allowed', 'placeholder-gray-300', 'dark:placeholder-gray-500'], ['text-gray-400', 'dark:text-gray-500']);
                                updateClasses(purchaseAmountLabel, ['text-gray-300', 'dark:text-gray-500'], []);
                                updateClasses(purchaseAmountPrefix, ['text-gray-300', 'dark:text-gray-600'], ['text-gray-400', 'dark:text-gray-500']);
                                updateClasses(purchaseAmountSuffix, ['text-gray-300', 'dark:text-gray-600'], ['text-gray-400', 'dark:text-gray-500']);
                                updateClasses(pointsInput, [], ['cursor-not-allowed']);

                                purchaseAmountInput.required = false;
                                purchaseAmountInput.disabled = true;
                                purchaseAmountInput.value = null;
                                pointsInput.required = true;
                                pointsInput.readOnly = false;
                                pointsInput.value = null;

                                purchaseAmountLabel.innerHTML = '{{ trans('common.purchase_amount') }}';
                                pointsLabel.innerHTML = '{{ trans('common.points') }}&nbsp;*';

                                pointsInput.focus();
                            } else {
                                updateClasses(purchaseAmountInput, ['text-gray-400', 'dark:text-gray-500'], ['cursor-not-allowed', 'placeholder-gray-300', 'dark:placeholder-gray-500']);
                                updateClasses(purchaseAmountLabel, [], ['text-gray-300', 'dark:text-gray-500']);
                                updateClasses(purchaseAmountPrefix, ['text-gray-400', 'dark:text-gray-500'], ['text-gray-300', 'dark:text-gray-600']);
                                updateClasses(purchaseAmountSuffix, ['text-gray-400', 'dark:text-gray-500'], ['text-gray-300', 'dark:text-gray-600']);
                                updateClasses(pointsInput, ['cursor-not-allowed'], []);

                                purchaseAmountInput.required = true;
                                purchaseAmountInput.disabled = false;
                                pointsInput.required = false;
                                pointsInput.readOnly = true;
                                pointsInput.value = null;

                                purchaseAmountLabel.innerHTML = '{{ trans('common.purchase_amount') }}&nbsp;*';
                                pointsLabel.innerHTML = '{{ trans('common.points') }}';

                                purchaseAmountInput.focus();
                            }
                        });

                        const round_points_up = {{ json_encode((bool) ($card->meta['round_points_up'] ?? true)) }};
                        const currency_unit_amount = {{ $card->currency_unit_amount }};
                        const points_per_currency = {{ $card->points_per_currency }};
                        const min_points_per_purchase = {{ $card->min_points_per_purchase }};
                        const max_points_per_purchase = {{ $card->max_points_per_purchase }};

                        purchaseAmountInput.addEventListener('input', function() {
                            if (!pointsOnly.checked) {
                                let pointsValue;

                                if (round_points_up) {
                                    pointsValue = Math.ceil((this.value / currency_unit_amount) * points_per_currency);
                                } else {
                                    pointsValue = Math.floor((this.value / currency_unit_amount) * points_per_currency);
                                }

                                if (pointsValue >= min_points_per_purchase && pointsValue <= max_points_per_purchase) {
                                    pointsInput.value = pointsValue;
                                } else if (pointsValue < min_points_per_purchase) {
                                    pointsInput.value = min_points_per_purchase;
                                } else if (pointsValue > max_points_per_purchase) {
                                    pointsInput.value = max_points_per_purchase;
                                }
                            }
                        });

                        document.querySelector('form').addEventListener('submit', function() {
                            this.querySelector('button[type="submit"]').disabled = true;
                        });
                    });
                </script>
            @endif

            @if($member)
                <x-member.member-card class="mb-6" :member="$member" />
            @else
                <div class="mb-6 format format-sm sm:format-base lg:format-md dark:format-invert">
                    <h3>{{ trans('common.member_not_found') }}</h3>
                </div>
            @endif

            @if($card)
                <x-member.card
                    :card="$card"
                    :member="$member"
                    :flippable="false"
                    :links="false"
                    :show-qr="false"
                />
                <a href="{{ route('member.card', ['card_id' => $card->id]) }}" target="_blank" class="mt-4 flex items-center text-link">
                    <x-ui.icon icon="arrow-top-right-on-square" class="w-5 h-5 mr-2"/>
                    {{ trans('common.view_card_on_website') }}
                </a>
            @else
                <div class="format format-sm sm:format-base lg:format-md dark:format-invert">
                    <h3>{{ trans('common.card_not_found') }}</h3>
                </div>
            @endif
        </div>
    </div>
</div>
@stop
