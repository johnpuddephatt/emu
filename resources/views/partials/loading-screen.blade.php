{{--
    Homepage splash, ported from the old eastmarshunited hero: the brand lines
    draw themselves over black, then the whole overlay fades out. The fade is
    pure CSS, so the overlay always clears itself even if JS never runs.

    The inline script only suppresses the splash for the rest of the session.
    It sits immediately before the overlay so it executes while the parser is
    still ahead of it — no flash of a splash that's about to be hidden.
--}}
<script>
    try {
        if (sessionStorage.getItem('emu-splash-seen')) {
            document.documentElement.classList.add('splash-seen');
        } else {
            sessionStorage.setItem('emu-splash-seen', '1');
        }
    } catch (e) {
        // Private browsing with storage denied: just show the splash.
    }
</script>

<div class="loading-screen" aria-hidden="true">
    @svg('vertical-lines-animated', 'loading-screen__lines')

    {{-- The old site centred the header logo over the lines and faded it out
         just ahead of them. The wordmark is knocked out of the badge, so it
         needs a dark shape behind it or the lines show through the letters —
         the old site used a rotated block for exactly this. --}}
    <div class="loading-screen__logo">
        <span aria-hidden="true" class="loading-screen__logo-backdrop"></span>

        @svg('logo', 'relative block h-auto w-full fill-white')
    </div>
</div>
