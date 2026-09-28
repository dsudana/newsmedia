@props(['title', 'description', 'subdescription'])

<div>
    <x-section-heading :title="$title" />
    <p class="text-[13px] font-semibold text-rn-ink">{{ $description }}</p>
    <p class="text-[12px] text-rn-muted mt-1">{{ $subdescription }}</p>

    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex mt-3">
        @csrf
        <input type="email" name="email" required placeholder="Your email address"
               class="flex-1 border border-rn-line px-3 py-2 text-[13px] focus:outline-none focus:border-rn-red">
        <button type="submit" class="bg-rn-red text-white text-[12px] font-bold uppercase px-4 hover:bg-rn-red-dark transition-colors">
            Sign Up
        </button>
    </form>
</div>
