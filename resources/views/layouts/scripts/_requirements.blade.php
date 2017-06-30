{{-- FRONTEND BROWSER REQUIREMENTS --}}

<script>
    (function(){
        try {
            return 'localStorage' in window && window['localStorage'] !== null
        } catch(e) {
            document.write('<div class="wall"><div><h1>This site requires cookies and local storage!</h1></div></div>')
            throw new Error("This website requires cookies and local storage")
        }
    })();
</script>
