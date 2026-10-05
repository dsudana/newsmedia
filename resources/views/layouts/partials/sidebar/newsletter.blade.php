<div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg border border-red-200 p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-900 mb-2">
        <i class="fas fa-envelope text-red-600 mr-2"></i>Newsletter
    </h3>
    <p class="text-sm text-gray-600 mb-4">Subscribe to get latest news and updates</p>

    <form class="space-y-3" onsubmit="handleNewsletterSubscribe(event)">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" required
            class="w-full px-4 py-2 border border-gray-400 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent">
        <button type="submit"
            class="w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700 transition-colors text-sm">
            Subscribe
        </button>
    </form>

    <p class="text-sm text-gray-600 mt-3">We don't spam. Unsubscribe at any time.</p>
</div>

<script>
    function handleNewsletterSubscribe(e) {
        e.preventDefault();
        const email = e.target.email.value;

        fetch('/api/newsletter/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    email
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('Thank you for subscribing!', 'success');
                    e.target.reset();
                } else {
                    showToast(data.message || 'Subscription failed', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('An error occurred', 'error');
            });
    }
</script>
