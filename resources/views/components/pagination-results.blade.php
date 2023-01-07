<div class="mt-6">
    {{ $results->links() }}

    @push('scripts')
        <script>
            Livewire.on('scrollTop', () => {
                main = document.getElementById('main')
                main.scrollIntoView();
            })
        </script>
    @endpush
</div>
