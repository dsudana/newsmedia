<div class="ad-slot my-4 text-center">
    @foreach($ads as $ad)
        <div class="mb-4">
            @if($ad->type === 'image')
                <a href="{{ $ad->url ?? '#' }}" target="_blank" rel="nofollow">
                    @if(!empty($ad->image))
                        <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->name }}" class="mx-auto max-w-full h-auto">
                    @endif
                </a>
            @elseif($ad->type === 'code' || $ad->type === 'script')
                {!! $ad->script !!}
            @endif
        </div>
    @endforeach
</div>