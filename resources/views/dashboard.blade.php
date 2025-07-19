<x-app-layout>
  <!-- content -->
  <div id="react-dashboard"></div>
  
  @vite(['resources/css/app.css','resources/js/app.jsx'])

  @push('scripts')
    <script>
      window.user = @json(auth()->user()->load('wallets'));
      window.csrf_token = '{{ csrf_token() }}';
    </script>
  @endpush
</x-app-layout>