{{-- EU Cookie usage warning --}}

@if(! $hasAccepted)
    <div class="Allow-cookies" id="allow-cookies">
        <span class="Allow-cookies__Message">This site requires enabled cookies to improve your experience.</span>

        <button class="Allow-cookies__Button" id="allow_cookies_btn">I Agree</button>
    </div>

    <script src="/js/accept-cookies.js"></script>
@endif
