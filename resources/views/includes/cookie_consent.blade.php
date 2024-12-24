@if(config('default.cookie_consent') && (request()->cookie('cookie_consent') == -1 || is_null(request()->cookie('cookie_consent'))))
<div id="cookie-consent-banner" tabindex="-1" aria-hidden="false" class="overflow-y-auto bg-black bg-opacity-50 overflow-x-hidden fixed z-50 w-full inset-0 h-modal h-full">
  <div class="relative w-full h-full md:h-auto">
      <div class="fixed w-full bg-white shadow dark:bg-gray-800 bottom-0">
          <div class="justify-between items-center p-5 lg:flex">
              <p class="mb-4 text-sm text-gray-500 dark:text-white lg:mb-0">
                {{ trans('common.cookie_consent_message') }}
              </p>
              <div class="items-center space-y-4 sm:space-y-0 sm:space-x-4 sm:flex lg:pl-10 shrink-0">
                  <a href="{{ route('member.privacy') }}" class="text-sm text-primary-600 dark:text-primary-500 hover:underline whitespace-nowrap">{{ trans('common.privacy_policy') }}</a>
                  <button id="block-cookies" type="button" class="py-2 px-4 w-full text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-auto hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 block-cookies">{{ trans('common.decline') }}</button>
                  <button id="accept-cookies" type="button" class="py-2 px-4 w-full text-sm font-medium text-center text-white rounded-lg bg-primary-700 sm:w-auto hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 accept-cookies">{{ trans('common.accept') }}</button>
                  <button id="close-modal" type="button" class="hidden md:flex text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white block-cookies">
                      <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                  </button>
              </div>
          </div>
      </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Function to make an AJAX call to set the cookie in Laravel
    function setConsentCookie(value) {
        const url = `{{ route('set.consent.cookie.post', ['value' => '']) }}/${value}`;
        fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('cookie-consent-banner').style.display = 'none';
            } else {
                console.error('Error setting the cookie.');
            }
        })
        .catch(error => {
            console.error('There was an error with the AJAX request:', error);
        });
    }

    // Event listeners for the "Accept" button
    document.querySelectorAll('.accept-cookies').forEach(function(button) {
        button.addEventListener('click', function() {
            setConsentCookie(1); // 1 means cookies are allowed
        });
    });

    // Event listeners for the "Decline" button
    document.querySelectorAll('.block-cookies').forEach(function(button) {
        button.addEventListener('click', function() {
            setConsentCookie(0); // 0 means cookies are not allowed
        });
    });
});
</script>
@endif