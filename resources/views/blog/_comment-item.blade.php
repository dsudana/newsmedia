<div class="dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
    <div class="flex items-start gap-4">
        <div class="w-12 h-12 rounded-full bg-red-200 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0 font-bold text-red-700 dark:text-red-400">
            {{ strtoupper(substr($comment->name, 0, 1)) }}
        </div>
        <div class="flex-1">
            <div class="flex items-center justify-between mb-2">
                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $comment->name }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $comment->created_at->format('M d, Y H:i') }}</p>
            </div>
            <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">{{ $comment->content }}</p>
            <div class="flex gap-3 text-sm">
                @can('reply', $comment)
                    <button class="text-blue-600 dark:text-blue-400 hover:underline reply-btn" data-comment-id="{{ $comment->id }}">
                        Reply
                    </button>
                @endcan
                @can('delete', $comment)
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline" onclick="return confirm('Delete this comment?')">Delete</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    <!-- Nested Replies -->
    @if($comment->replies->count() > 0)
        <div class="mt-6 ml-8 space-y-4 border-l-2 border-gray-300 dark:border-gray-700 pl-4">
            @foreach($comment->replies as $reply)
                <div class="bg-white dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-200 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 font-bold text-blue-700 dark:text-blue-400">
                            {{ strtoupper(substr($reply->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $reply->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed mb-2">{{ $reply->content }}</p>
                            @can('delete', $reply)
                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-xs" onclick="return confirm('Delete this reply?')">Delete</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Reply Form (hidden by default) -->
    <div class="reply-form hidden mt-4 ml-8 pt-4 border-t border-gray-300 dark:border-gray-700" data-comment-id="{{ $comment->id }}">
        <form action="{{ route('comments.store', $article->slug) }}" method="POST" class="space-y-3">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <input type="text" name="name" placeholder="Your name" required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded focus:ring-2 focus:ring-red-500 placeholder-gray-400 dark:placeholder-gray-500">
            <input type="email" name="email" placeholder="Your email" required class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded focus:ring-2 focus:ring-red-500 placeholder-gray-400 dark:placeholder-gray-500">
            <textarea name="content" placeholder="Your reply..." required rows="3" class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded focus:ring-2 focus:ring-red-500 placeholder-gray-400 dark:placeholder-gray-500"></textarea>
            <div class="flex gap-2">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 text-sm rounded hover:bg-red-700">Reply</button>
                <button type="button" class="cancel-reply-btn border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-gray-100" data-comment-id="{{ $comment->id }}">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`.reply-form[data-comment-id="${commentId}"]`);
            replyForm.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('.cancel-reply-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`.reply-form[data-comment-id="${commentId}"]`);
            replyForm.classList.add('hidden');
        });
    });
});
</script>
