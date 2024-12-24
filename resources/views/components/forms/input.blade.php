<div {!! $class ? 'class="' . $class . '"' : '' !!}>
    @if ($label || $rightText)
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
    @endif

    <div class="flex space-x-2 rtl:space-x-reverse" x-data="{ input: '{{ $type }}' }">
        <div class="relative w-full">
            @if ($icon)
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <x-ui.icon :icon="$icon" :class="($affixClass) ? $affixClass . ' w-5 h-5' : 'input-icon'" />
                </div>
            @endif
            @if ($prefix)
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <span class="{{ $affixClass ?? 'text-gray-600 dark:text-gray-300 sm:text-sm' }}" id="{{ $id }}_prefix">{{ $prefix }}</span>
                </div>
                <script>
                    const {{ $id }}_prefix_label = document.getElementById('{{ $id }}_prefix');
                    const {{ $id }}_prefix_input = document.getElementById('{{ $id }}');
                    const {{ $id }}_prefix_observer = new IntersectionObserver((entries, {{ $id }}_prefix_observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                {{ $id }}_suffix_input.style.paddingLeft = {{ $id }}_prefix_label.offsetWidth + 20 + 'px';
                                {{ $id }}_prefix_observer.disconnect();
                            }
                        });
                    });
                    {{ $id }}_prefix_observer.observe({{ $id }}_prefix_label);
                </script>
            @endif

            <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" class="@if ($type == 'color') h-16 @endif bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @if ($icon) pl-10 @endif @error($nameToDotNotation) is-invalid @enderror {{ $inputClass }}" placeholder="{{ $placeholder }}" @if ($required) required @endif @if ($autofocus) autofocus @endif {{ $attributes }} x-bind:type="input" @error($nameToDotNotation) onkeydown="this.classList.remove('is-invalid')" @enderror @if ($type == 'range') oninput="document.getElementById('{{ $id }}_output').value = this.value" @endif>
            @if ($suffix)
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                    <span class="{{ $affixClass ?? 'text-gray-600 dark:text-gray-300 sm:text-sm' }}" id="{{ $id }}_suffix">{{ $suffix }}</span>
                </div>
                <script>
                    const {{ $id }}_suffix_label = document.getElementById('{{ $id }}_suffix');
                    const {{ $id }}_suffix_input = document.getElementById('{{ $id }}');
                    const {{ $id }}_suffix_observer = new IntersectionObserver((entries, {{ $id }}_suffix_observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                {{ $id }}_suffix_input.style.paddingRight = {{ $id }}_suffix_label.offsetWidth + 20 + 'px';
                                {{ $id }}_suffix_observer.disconnect();
                            }
                        });
                    });
                    {{ $id }}_suffix_observer.observe({{ $id }}_suffix_label);
                </script>
            @endif
        </div>
        @if ($type == 'range') 
            <div class="flex space-x-2 rtl:space-x-reverse">
                <input type="number" id="{{ $id }}_output" {{ $attributes }} name="{{ $name }}" value="{{ $value }}" oninput="document.getElementById('{{ $id }}').value = this.value" class="bg-gray-50 border border-gray-300 text-center text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 @if ($icon) pl-10 @endif @error($nameToDotNotation) is-invalid @enderror">
            </div>
        @endif
        @if ($type == 'password')
            <input id="{{ $id }}_changed" type="hidden" value="" />
            <button type="button" tabindex="-1" class="flex-1 btn px-3 focus:ring-2 focus:ring-primary-600 borderfocus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500 border border-gray-300 dark:border-gray-600" x-on:click="input = (input === 'password') ? 'text' : 'password'">
                <svg x-show="input === 'password'" aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <svg x-show="input != 'password'" aria-hidden="true" class="w-4 h-4" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </button>
            @if($generatePassword)
                <button type="button" tabindex="-1" class="flex-1 btn px-3 focus:ring-2 focus:ring-primary-600 borderfocus:border-primary-600 dark:focus:ring-primary-500 dark:focus:border-primary-500 border border-gray-300 dark:border-gray-600" onClick="document.getElementById('{{ $id }}').value = generatePassword(8)">
                    <x-ui.icon icon="refresh" class="w-4 h-4" data-fb="tooltip" title="{!! parse_attr(trans('common.generate_password')) !!}" />
                </button>
            @endif
        @endif
    </div>
    <div class="flex space-x-2 rtl:space-x-reverse">
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
    @if($mailPassword)
        <x-forms.checkbox class="mt-3" name="send_user_password" :checked="$mailPasswordChecked" :label="trans('common.send_user_password')" />
    @endif
</div>
