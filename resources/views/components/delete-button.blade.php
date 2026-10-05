@props(['route', 'model' => 'item', 'title' => 'Delete Item', 'size' => 'normal'])

<form action="{{ $route }}" method="POST" class="inline delete-form">
    @csrf
    @method('DELETE')
    <button type="button"
        class="delete-btn p-2 rounded-lg transition {{ $size === 'small' ? 'text-sm' : '' }}"
        :class="'text-red-600 hover:bg-red-50'"
        :title="'Delete'">
        <i class="fas fa-trash"></i>
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'Delete {{ $model }}?',
                text: "You won't be able to undo this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
