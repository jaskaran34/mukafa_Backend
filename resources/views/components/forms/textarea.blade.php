<div {!! $class ? 'class="' . $class . '"' : '' !!}>
    <div class="flex">
        @if ($label)
            <label for="{{ $id }}" class="input-label {{ $classLabel }} @error($nameToDotNotation) is-invalid-label @enderror">
                {!! $label !!}
            </label>
            @if ($help)
                <div data-fb="tooltip" title="{!! parse_attr($help) !!}" class="ml-2 rtl:mr-2">
                    <x-ui.icon icon="info" class="h-4 w-4 text-gray-400 hover:text-gray-500" />
                </div>
            @endif
        @endif
        @if ($rightText && $rightPosition == 'top')
            <div class="flex-1 items-center text-right text mb-2">
                @if ($rightLink)
                    <a href="{{ $rightLink }}" class="text-link">
                @endif
                {!! $rightText !!}
                @if ($rightLink)
                    </a>
                @endif
            </div>
        @endif
        @if($ai)
            @php
                $aiName = $name;
                $locale = null;
                if (preg_match('/^(.*)\[(.*)\]$/', $name, $matches)) {
                    $aiName = $matches[1];
                    $locale = $matches[2];
                }
            @endphp
            <div class="flex-1 flex items-start justify-end">
                <div class="relative top-[-4px] ml-1">
                    <button type="button" class="flex items-center text-sm p-1 rounded-full focus:ring focus:ring-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-white" id="{{ $id }}_ai-menu-button" aria-expanded="false" data-dropdown-toggle="{{ $id }}_ai-dropdown" data-dropdown-placement="left-start" {{ $value ? '' : 'disabled' }} @if(isset($ai['autoFill']) && isset($ai['autoFillPrompt']) && $ai['autoFill'] && isset($form['view']) && $form['view'] == 'insert') data-ai-autofill data-target-id="{{ $id }}" data-meta='{{ json_encode(['field' => $aiName, 'locale' => $locale]) }}' @endif>
                        <span class="sr-only">Open AI menu</span>
                        <x-ui.icon icon="sparkles" class="w-4 h-4" />
                        <div id="{{ $id }}_ai-indicator" class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-primary-500 border-2 border-white rounded-md -top-2.5 -end-2.5 dark:border-gray-900">AI</div>
                    </button>
                    <div class="hidden min-w-52 z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-md drop-shadow-md dark:bg-gray-700 dark:divide-gray-600" id="{{ $id }}_ai-dropdown">
                        @if(config('prompts.prompts'))
                            <ul class="py-1 text-gray-600 dark:text-gray-400" aria-labelledby="{{ $id }}_ai-menu-button">
                                @foreach(config('prompts.prompts') as $action => $prompt)
                                    @if($action == 'divider')
                            </ul>
                            <ul class="py-1 text-gray-600 dark:text-gray-400" aria-labelledby="{{ $id }}_ai-menu-button_{{ $loop->index }}">
                                    @else
                                        @if(isset($prompt['hasSub']) && $prompt['hasSub'] && (($action == 'translate' && count($languages['all'] ?? []) > 1) || $action != 'translate'))
                                            <li>
                                                <button id="{{ $id }}_ai_btn_{{ $action }}" data-dropdown-toggle="{{ $id }}_ai_btn_{{ $action }}_dropdown" data-dropdown-placement="right-start" type="button" class="flex items-center justify-between w-full text-left rtl:text-right text-sm px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                                    <x-ui.icon :icon="$prompt['icon']" class="flex-shrink-0 w-4 h-4 mr-1.5 rtl:ml-1.5 text-gray-600 dark:text-gray-400" />
                                                    <span class="flex-grow">{{ trans('common.' . $action) }}</span>
                                                    <x-ui.icon icon="chevron-right" class="flex-shrink-0 w-3 h-3 ms-3 text-gray-600 dark:text-gray-400" /></button>
                                                <div id="{{ $id }}_ai_btn_{{ $action }}_dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-md drop-shadow-md dark:bg-gray-700 dark:divide-gray-600 min-w-44">
                                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{ $id }}_ai_btn_{{ $action }}">
                                                        @if($action == 'translate' && count($languages['all'] ?? []) > 1)
                                                            @foreach ($languages['all'] as $language)
                                                                <button id="{{ $id }}_ai_translate_btn_{{ $language['locale'] }}" type="button" data-type="ai" data-action="translate" data-target-id="{{ $id }}" data-meta='{{ json_encode(['field' => $aiName, 'locale' => $locale,'translate_to_locale' => $language['locale']]) }}' class="block w-full text-left rtl:text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">
                                                                    <div class="inline-flex items-center">
                                                                        <div class="w-3.5 h-3.5 mr-2.5 rtl:ml-2.5 rounded-full fis fi-{{ strtolower($language['countryCode']) }}"></div>
                                                                        {{ $language['languageName'] }}
                                                                    </div>
                                                                </button>
                                                            @endforeach
                                                        @elseif($action != 'translate')
                                                            @foreach ($prompt['templates'] as $subAction => $subPrompt)
                                                                <button id="{{ $id }}_ai_{{ $action }}_btn_{{ $loop->index }}" type="button" data-type="ai" data-action="{{ $action . '.templates.' . $subAction }}" data-target-id="{{ $id }}" data-meta='{{ json_encode(['field' => $aiName, 'locale' => $locale]) }}' class="block w-full text-left rtl:text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">
                                                                    <div class="inline-flex items-center">
                                                                        {{ trans('common.' . $subAction) }}
                                                                    </div>
                                                                </button>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                </div>
                                            </li>
                                        @else
                                            <li>
                                                <button id="{{ $id }}_ai_btn_{{ $loop->index }}" type="button" data-type="ai" data-action="{{ $action }}" data-target-id="{{ $id }}" data-meta='{{ json_encode(['field' => $aiName, 'locale' => $locale]) }}' class="inline-flex items-center text-left rtl:text-right w-full px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-400 dark:hover:text-white">
                                                    <x-ui.icon :icon="$prompt['icon']" class="flex-shrink-0 w-4 h-4 mr-1.5 rtl:ml-1.5 text-gray-600 dark:text-gray-400" />
                                                    <span class="flex-grow">{{ trans('common.' . $action) }}</span>
                                                </button>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="flex space-x-2" x-data="{ input: '{{ $type }}' }">
        <div class="relative w-full">
            @if ($icon)
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="input-icon">
                        @if ($icon == 'envelope')
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        @endif
                        @if ($icon == 'key')
                            <path fill-rule="evenodd" d="M8 7a5 5 0 113.61 4.804l-1.903 1.903A1 1 0 019 14H8v1a1 1 0 01-1 1H6v1a1 1 0 01-1 1H3a1 1 0 01-1-1v-2a1 1 0 01.293-.707L8.196 8.39A5.002 5.002 0 018 7zm5-3a.75.75 0 000 1.5A1.5 1.5 0 0114.5 7 .75.75 0 0016 7a3 3 0 00-3-3z" clip-rule="evenodd" />
                        @endif
                    </svg>
                </div>
            @endif
            <textarea rows="{{ $attributes->get('rows', 5) }}" id="{{ $id }}" name="{{ $name }}" class="@if ($type == 'color') h-16 @endif bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @if ($icon) pl-10 @endif @error($nameToDotNotation) is-invalid @enderror" placeholder="{{ $placeholder }}" @if ($required) required @endif @if ($autofocus) autofocus @endif {{ $attributes->except('rows') }} x-bind:type="input" @error($nameToDotNotation) onkeydown="this.classList.remove('is-invalid')" @enderror @if ($type == 'range') oninput="document.getElementById('{{ $id }}_output').value = this.value" @endif>{{ $value }}</textarea>
        </div>
    </div>
    <div class="flex space-x-2">
        @error($nameToDotNotation)
            <div class="invalid-msg">
                {{ $errors->first($nameToDotNotation) }}
            </div>
        @else
            @if ($text)
                <p class="form-help-text">{!! $text !!}</p>
            @endif
        @enderror

        @if ($rightText && $rightPosition == 'bottom')
            <div class="flex-1 items-center text-right text mt-2">
                @if ($rightLink)
                    <a href="{{ $rightLink }}" class="text-link">
                @endif
                {!! $rightText !!}
                @if ($rightLink)
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
