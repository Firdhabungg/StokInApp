@if (session('success') || session('error') || session('info') || session('warning'))
    <div id="flashAlert" 
        class="mb-4 px-4 py-3 rounded-lg flex items-center justify-between transition-all duration-300
        @if(session('success')) bg-green-50 border border-green-200 text-green-700
        @elseif(session('error')) bg-red-50 border border-red-200 text-red-700
        @elseif(session('warning')) bg-yellow-50 border border-yellow-200 text-yellow-700
        @else bg-blue-50 border border-blue-200 text-blue-700
        @endif">
        <div class="flex items-center">
            <i class="mr-2 
                @if(session('success')) fas fa-check-circle
                @elseif(session('error')) fas fa-times-circle
                @elseif(session('warning')) fas fa-exclamation-triangle
                @else fas fa-info-circle
                @endif"></i>
            <span>{{ session('success') ?? session('error') ?? session('info') ?? session('warning') }}</span>
        </div>
        <button type="button" onclick="closeFlashAlert()" class="text-current opacity-70 hover:opacity-100 ml-4">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
        // Auto-close after 5 seconds
        setTimeout(function() {
            closeFlashAlert();
        }, 5000);

        function closeFlashAlert() {
            const alert = document.getElementById('flashAlert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            }
        }
    </script>
@endif
