@props(['placeholder' => 'Your email', 'buttonText' => 'Subscribe', 'isDarkBg' => false])

<form class="space-y-2 sm:space-y-3"
    x-data="newsletterForm()"
    @submit.prevent="submitNewsletter">
    @csrf
    <input
        type="email"
        name="email"
        placeholder="{{ $placeholder }}"
        x-model="email"
        class="w-full px-3 sm:px-4 py-2 rounded-lg {{ $isDarkBg ? 'bg-gray-700 text-white placeholder-gray-400 focus:ring-red-500' : 'bg-white/90 text-gray-900 focus:ring-red-400' }} text-xs sm:text-sm focus:outline-none focus:ring-2 transition"
        required>
    <button
        type="submit"
        :disabled="loading"
        class="w-full {{ $isDarkBg ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-white text-red-600 hover:bg-red-50' }} font-semibold py-2 rounded-lg transition text-xs sm:text-sm disabled:opacity-50">
        <span x-show="!loading">{{ $buttonText }}</span>
        <span x-show="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Subscribing...</span>
    </button>
</form>

<script>
    function newsletterForm() {
        return {
            loading: false,
            email: '',
            submitNewsletter() {
                this.loading = true;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: this.email })
                })
                .then(response => response.json())
                .then(data => {
                    this.loading = false;
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Subscribed!',
                            text: 'Check your inbox',
                            timer: 3000
                        });
                        this.email = '';
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: data.message || 'Subscription failed'
                        });
                    }
                })
                .catch(error => {
                    this.loading = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong'
                    });
                    console.error('Newsletter error:', error);
                });
            }
        };
    }
</script>
