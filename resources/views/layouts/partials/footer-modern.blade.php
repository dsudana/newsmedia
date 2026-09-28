<!-- Footer -->
<footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Footer Content Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
            <div>
                <h3 class="font-bold text-white text-sm mb-4">World</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition">Global Economy</a></li>
                    <li><a href="#" class="hover:text-white transition">Politic</a></li>
                    <li><a href="#" class="hover:text-white transition">Business</a></li>
                    <li><a href="#" class="hover:text-white transition">Conflict</a></li>
                    <li><a href="#" class="hover:text-white transition">Sports</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-white text-sm mb-4">Entertainment</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition">Celebrity News</a></li>
                    <li><a href="#" class="hover:text-white transition">Artistes</a></li>
                    <li><a href="#" class="hover:text-white transition">Music</a></li>
                    <li><a href="#" class="hover:text-white transition">Life Style</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-white text-sm mb-4">Health</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition">Medical Research</a></li>
                    <li><a href="#" class="hover:text-white transition">Diet</a></li>
                    <li><a href="#" class="hover:text-white transition">Virus Genetic</a></li>
                    <li><a href="#" class="hover:text-white transition">Children's Health</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-white text-sm mb-4">Business</h3>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white transition">Inovation</a></li>
                    <li><a href="#" class="hover:text-white transition">Technology</a></li>
                    <li><a href="#" class="hover:text-white transition">Property</a></li>
                    <li><a href="#" class="hover:text-white transition">Business Startups</a></li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-1 hover:opacity-80 transition">
                <span class="text-xl font-black text-white">RET</span>
                <i class="fas fa-bolt text-red-600 text-xl"></i><span
                    class="text-xl font-black text-red-600">NEWS</span>
            </a>
            <div class="flex items-center gap-4">
                <a href="#" class="text-gray-400 hover:text-red-600 transition text-lg"><i
                        class="fab fa-facebook"></i></a>
                <a href="#" class="text-gray-400 hover:text-red-600 transition text-lg"><i
                        class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-400 hover:text-red-600 transition text-lg"><i
                        class="fab fa-linkedin"></i></a>
                <a href="#" class="text-gray-400 hover:text-red-600 transition text-lg"><i
                        class="fab fa-instagram"></i></a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center text-xs text-gray-500 mt-6 pt-6 border-t border-gray-800">
            <p>&copy; {{ date('Y') }} NewSMedia. All rights reserved.</p>
        </div>
    </div>
</footer>
