@extends('staff.layouts.default')

@section('page_title', trans('common.scan_qr') . config('default.page_title_delimiter') . trans('common.dashboard') . config('default.page_title_delimiter') . config('default.app_name'))

@section('content')
<section class="bg-gray-50 dark:bg-gray-900 w-full">
    <div class="py-8 px-4 mx-auto sm:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-md text-center mb-8 lg:mb-16">

            <x-ui.button
                class="scan-qr disable-on-scan mb-4"
                :text="trans('common.scan_qr')"
                icon="qr-code"
            />

            <div id="scanner-info" class="hide-on-scan z-40 mb-4 flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
                    <x-ui.icon icon="info" class="w-5 h-5"/>
                </div>
                <div class="ml-3 text-sm font-normal">{{ trans('common.qr_scanner_info') }}</div>
            </div>

            <div id="code-found" class="hidden z-40 flex items-center w-full p-4 text-gray-500 bg-white rounded-lg shadow dark:text-gray-400 dark:bg-gray-800" role="alert">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200">
                    <x-ui.icon icon="check" class="w-5 h-5"/>
                </div>
                <div class="ml-3 text-sm font-normal">{{ trans('common.code_found') }}</div>
            </div>

            <video id="video" class="w-full rounded-md"></video>
        </div>
    </div>
</section>

<script>
window.onload = function() {
    const codeFound = document.getElementById('code-found');

    // Listen to the pageshow event
    window.addEventListener('pageshow', function(event) {
        // If the page is loaded from the cache (like when using the back button)
        if (event.persisted) {
            // Hide the codeFound element
            codeFound.classList.add('hidden');
        }
    });
};
</script>
@stop
