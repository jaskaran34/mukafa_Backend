@if (session('toast'))
    @php
        $toastType = session('toast')['type'];
        $toastSize = session('toast')['size'] ?? 'sm';
        $toastText = session('toast')['text'];
        $iconMap = [
            'success' => 'check',
            'warning' => 'exclamation-triangle',
            'danger' => 'x-mark'
        ];
        $bgColorMap = [
            'success' => 'bg-green-100 dark:bg-green-800',
            'warning' => 'bg-orange-100 dark:bg-orange-700',
            'danger' => 'bg-red-100 dark:bg-red-800'
        ];
        $textColorMap = [
            'success' => 'text-green-500 dark:text-green-200',
            'warning' => 'text-orange-500 dark:text-orange-200',
            'danger' => 'text-red-500 dark:text-red-200'
        ];
    @endphp

    <div id="toast-{{ $toastType }}"
        class="fixed left-1/2 top-5 transform -translate-x-1/2 z-40 flex items-center w-full max-w-{{ $toastSize }} p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-900"
        role="alert">
        <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 {{ $textColorMap[$toastType] }} {{ $bgColorMap[$toastType] }} rounded-lg">
            <x-ui.icon icon="{{ $iconMap[$toastType] }}" class="w-5 h-5" />
        </div>
        <div class="ml-3 text-sm font-normal">{{ $toastText }}</div>
        <button type="button"
            class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
            data-dismiss-target="#toast-{{ $toastType }}" aria-label="Close">
            <span class="sr-only">{{ trans('common.close') }}</span>
            <x-ui.icon icon="x-mark" class="w-5 h-5" />
        </button>
    </div>

    <script>
        window.onload = function() {
            setTimeout(fadeOutToast, 5000);
        };

        function fadeOutToast() {
            const toastEl = document.getElementById('toast-{{ $toastType }}');
            new Flowbite.Dismiss(toastEl).hide();
        }
    </script>
@endif
