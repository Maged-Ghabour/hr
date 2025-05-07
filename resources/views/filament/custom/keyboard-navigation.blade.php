@once
<script>
    document.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowRight') {
                const previousUrl = @json($previousUrl);
                if (previousUrl) {
                    window.location.href = previousUrl;
                }
            }

            if (event.key === 'ArrowLeft') {
                const nextUrl = @json($nextUrl);
                if (nextUrl) {
                    window.location.href = nextUrl;
                }
            }
        });
</script>
@endonce