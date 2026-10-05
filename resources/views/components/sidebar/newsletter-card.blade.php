@props([
    'title' => 'Newsletter',
    'subtitle' => 'Get the latest stories delivered to your inbox daily',
    'placeholder' => 'your@email.com',
    'buttonText' => 'Subscribe'
])

<section class="bg-gradient-to-br from-red-600 to-red-700 rounded-lg p-6 text-white">
    <h3 class="text-lg font-bold mb-2">{{ $title }}</h3>
    <p class="text-sm text-red-100 mb-4">{{ $subtitle }}</p>
    <x-frontend.newsletter-form :placeholder="$placeholder" :buttonText="$buttonText" />
</section>
