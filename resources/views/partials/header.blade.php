<header id="header-main" class="bg-white border-b border-rn-line">
    <div class="max-w-6xl mx-auto px-4 lg:px-8 h-[76px] flex items-center justify-between">

         

        {{-- Logo: "RET" ink black + red lightning bolt + "NEWS" red --}}
        <a id="logo" href="{{ route('home') }}" class="flex items-center gap-1 select-none">
            <span class="text-2xl font-extrabold text-rn-ink tracking-tight">RET</span>
            <svg class="w-5 h-7 text-rn-red -mx-0.5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M13 2 3 14h7l-1 8 11-14h-7l1-6z" />
            </svg>
            <span class="text-2xl font-extrabold text-rn-red tracking-tight">NEWS</span>
        </a>

        {{-- Primary nav --}}
        <nav id="nav-primary"
            class="hidden lg:flex items-center gap-8 text-[13px] font-semibold text-rn-ink uppercase tracking-wide">
            @foreach ([['label' => 'Home', 'route' => 'home', 'dropdown' => true], ['label' => 'Pages', 'route' => 'pages', 'dropdown' => true], ['label' => 'About', 'route' => 'about', 'dropdown' => true], ['label' => 'News', 'route' => 'news.index', 'dropdown' => true], ['label' => 'Category', 'route' => 'category.index', 'dropdown' => false], ['label' => 'Contact', 'route' => 'contact', 'dropdown' => false]] as $item)
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-1 hover:text-rn-red transition-colors">
                    {{ $item['label'] }}
                    @if ($item['dropdown'])
                        <svg class="w-2.5 h-2.5 mt-px" viewBox="0 0 10 6" fill="none">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </nav>

        <button id="search-toggle" type="button" aria-label="Search"
            class="text-rn-ink hover:text-rn-red transition-colors">
            <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </button>
    </div>
</header>
