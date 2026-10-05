<footer class="bg-white dark:bg-gray-900">
    <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
        <div class="md:flex md:justify-between">
            <div class="mb-6 md:mb-0">
                <a href="https://flowbite.com/" class="flex items-center">
                    <img src="https://flowbite.com/docs/images/logo.svg" class="h-8 me-3" alt="FlowBite Logo" />
                    <span
                        class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">{{ $appname }}</span>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Resources</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="https://flowbite.com/" class="hover:underline">Flowbite</a>
                        </li>
                        <li>
                            <a href="https://tailwindcss.com/" class="hover:underline">Tailwind CSS</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Follow Kami:</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium space-y-2">
                        @php
                            $socialLinks = [
                                ['key' => 'social_facebook', 'label' => 'Facebook'],
                                ['key' => 'social_instagram', 'label' => 'Instagram'],
                                ['key' => 'social_x', 'label' => 'X (Twitter)'],
                                ['key' => 'social_pinterest', 'label' => 'Pinterest'],
                                ['key' => 'social_whatsapp', 'label' => 'WhatsApp'],
                            ];
                        @endphp
                        @foreach($socialLinks as $social)
                            @php
                                $url = setting($social['key']);
                            @endphp
                            @if($url)
                                <li>
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="hover:underline">{{ $social['label'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                    <ul class="text-gray-500 dark:text-gray-400 font-medium">
                        <li class="mb-4">
                            <a href="#" class="hover:underline">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="#" class="hover:underline">Terms &amp; Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700/50 lg:my-8" />
        <div class="sm:flex sm:items-center sm:justify-between">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© {{ date('Y') }} <a href="/"
                    class="hover:underline">{{ $appname }}</a>. All Rights Reserved.
            </span>
            <div class="flex mt-4 sm:justify-center sm:mt-0">
                <x-frontend.social-links />
            </div>
        </div>
    </div>
</footer>
