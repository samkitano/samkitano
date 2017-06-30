{{-- FRONTEND FOOTER SECTION --}}

<footer class="Footer">
    <span class="Footer__Copy-symbol">&copy;&nbsp;</span
    >Sam Kitano &nbsp;2016 - {{ \Carbon\Carbon::now()->year }} |&nbsp;<a
        class="hint--top-right"
        aria-label="Follow me on Twitter"
        target="_blank"
        href="https://twitter.com/SamKitano"
        ><div class="Svg">
            <svg class="Svg__Icon"
                 role="img"
                 aria-label="Follow me on Twitter"
                 width="20"
                 height="20"
                 viewBox="0 0 29 29">{!! PATH_SVG_TWITTER !!}<title>Follow me on Twitter</title></svg
            ></div></a
    >&nbsp;&nbsp;<a
            class="hint--top-right"
            aria-label="Github"
            target="_blank"
            href="https://github.com/samkitano"
            ><div class="Svg">
                <svg class="Svg__Icon"
                     role="img"
                     aria-label="Github"
                     width="16"
                     height="16"
                     viewBox="0 0 1024 1024">{!! PATH_SVG_GITHUB !!}<title>Github</title></svg
            ></div></a
    >&nbsp;|&nbsp;<a
            class="hint--top-right"
            aria-label="Privacy Policy"
            id="p_p_trigger"
            href="#"
            ><div class="Svg">
                <svg class="Svg__Icon"
                     role="img"
                     aria-label="Privacy Policy"
                     width="16"
                     height="16"
                     viewBox="0 0 200 200">{!! PATH_SVG_PRIVACY !!}<title>Privacy Policy</title></svg
            ></div></a
    >

    @include('layouts.partials._privacy-policy')
</footer>
