<footer class="bg-gray-800 text-white mt-12 py-8">
    <div class="container mx-auto px-4">
        <x-ad-slot placement="footer_top" />
    </div>

    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- About -->
        <div>
            <h4 class="text-lg font-bold mb-4">About Us</h4>
            <p class="text-gray-400 text-sm">
                {{ $settings['site_description'] ?? 'Your source for the latest news and updates.' }}
            </p>
            <div class="mt-4 flex space-x-4">
                @if(isset($settings['social_facebook']))
                    <a href="{{ $settings['social_facebook'] }}" class="text-gray-400 hover:text-white"><i
                            class="fab fa-facebook"></i> FB</a>
                @endif
                @if(isset($settings['social_twitter']))
                    <a href="{{ $settings['social_twitter'] }}" class="text-gray-400 hover:text-white"><i
                            class="fab fa-twitter"></i> TW</a>
                @endif
                {{-- Add other social links --}}
            </div>
        </div>

        <!-- Links -->
        <div>
            <h4 class="text-lg font-bold mb-4">Quick Links</h4>
            <ul class="text-gray-400 text-sm space-y-2">
                <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                <li><a href="#" class="hover:text-white">About</a></li>
                <li><a href="#" class="hover:text-white">Contact</a></li>
                <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
            </ul>
        </div>

        <!-- Categories -->
        <div>
            <h4 class="text-lg font-bold mb-4">Categories</h4>
            <ul class="text-gray-400 text-sm space-y-2">
                @foreach(\App\Models\Category::whereNull('parent_id')->take(5)->get() as $category)
                    <li><a href="{{ route('categories.show', $category) }}"
                            class="hover:text-white">{{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>

        <!-- Newsletter -->
        <div>
            <h4 class="text-lg font-bold mb-4">Newsletter</h4>
            <p class="text-gray-400 text-sm mb-4">Subscribe to get the latest news updates.</p>
            <form action="#" method="POST">
                <input type="email" placeholder="Your email"
                    class="w-full bg-gray-700 text-white border-none rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 mb-2">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded">Subscribe</button>
            </form>
        </div>
    </div>

    <div class="container mx-auto px-4 mt-8 border-t border-gray-700 pt-4 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} {{ $settings['site_name'] ?? config('app.name') }}. All rights reserved.
        {{ $settings['footer_text'] ?? '' }}
    </div>
</footer>