<x-admin.layout-modern>
    <div class="space-y-6 pr-4">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Categories</h1>
                <p class="text-sm text-gray-600 mt-1">Organize your content with categories</p>
            </div>
            <button onclick="openCategoryModal()" type="button"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:shadow-lg transition-shadow flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>New Category</span>
            </button>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-4 rounded-lg flex items-center gap-3">
                <i class="fas fa-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Total Categories</p>
                        <p class="text-3xl font-bold text-gray-900 mt-1">{{ \App\Models\Category::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-folder text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Articles</p>
                        <p class="text-3xl font-bold text-purple-600 mt-1">{{ \App\Models\Article::count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-newspaper text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-600 font-semibold uppercase">Avg. Per Category</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">
                            {{ \App\Models\Category::count() > 0 ? round(\App\Models\Article::count() / \App\Models\Category::count()) : 0 }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-bar text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($categories as $category)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow overflow-hidden group">
                    <!-- Header -->
                    <div class="h-32 bg-gradient-to-br from-indigo-400 to-purple-600 relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                        <div class="absolute top-4 right-4 flex gap-2">
                            <button onclick="editCategoryModal({{ $category->id }})"
                                class="p-2 bg-white/20 hover:bg-white/30 rounded-lg transition text-white"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteCategory({{ $category->id }})"
                                class="p-2 bg-white/20 hover:bg-red-500/80 rounded-lg transition text-white"
                                title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 -mt-8 relative z-10">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 shadow-lg">
                            <i class="fas fa-folder-open text-white text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $category->name }}</h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                            {{ $category->description ?? 'No description' }}</p>

                        <!-- Stats -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div>
                                <p class="text-xs text-gray-600 font-semibold">Articles</p>
                                <p class="text-lg font-bold text-indigo-600 mt-1">
                                    {{ $category->articles->count() ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-600 font-semibold">Slug</p>
                                <p class="text-xs text-gray-900 font-mono mt-1">{{ $category->slug }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 font-medium text-lg mb-2">No categories found</p>
                        <p class="text-gray-500 text-sm">Create your first category to organize your articles</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if (method_exists($categories, 'hasPages') && $categories->hasPages())
            <div class="flex justify-center">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Category Modal -->
    <div id="categoryModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" onclick="if(event.target.id === 'categoryModal') closeCategoryModal()">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 flex items-center justify-between">
                <h3 id="modalTitle" class="text-lg font-bold text-white">New Category</h3>
                <button onclick="closeCategoryModal()" class="text-white/80 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="categoryForm" class="p-6 space-y-3">
                @csrf
                <input type="hidden" id="categoryId" value="">
                <input type="hidden" id="methodField" name="_method" value="POST">

                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1.5">Category Name *</label>
                    <input type="text" id="categoryName" name="name" required placeholder="Enter category name" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>

                <!-- Description Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1.5">Description</label>
                    <textarea id="categoryDescription" name="description" rows="2" placeholder="Add a brief description..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                </div>

                <!-- Parent Category Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-1.5">Parent Category</label>
                    <select id="categoryParent" name="parent_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition bg-white">
                        <option value="">None</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 font-medium text-sm transition transform hover:scale-105">
                        <i class="fas fa-check mr-1.5"></i>Save
                    </button>
                    <button type="button" onclick="closeCategoryModal()" class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg hover:bg-gray-200 font-medium text-sm transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const baseUrl = '/admin/categories';

        function openCategoryModal() {
            document.getElementById('modalTitle').textContent = 'New Category';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
            document.getElementById('methodField').value = 'POST';
            document.getElementById('categoryModal').classList.remove('hidden');
            loadParentCategories();
        }

        function editCategoryModal(id) {
            fetch(`${baseUrl}/${id}/edit`, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(r => r.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit Category';
                document.getElementById('categoryId').value = id;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('categoryName').value = data.category.name;
                document.getElementById('categoryDescription').value = data.category.description || '';
                document.getElementById('categoryParent').value = data.category.parent_id || '';

                // Populate parents
                const select = document.getElementById('categoryParent');
                select.innerHTML = '<option value="">None</option>';
                data.parents.forEach(p => {
                    select.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                });

                document.getElementById('categoryModal').classList.remove('hidden');
            })
            .catch(err => console.error('Error loading category:', err));
        }

        function closeCategoryModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function loadParentCategories() {
            fetch(`${baseUrl}/create`, {
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            })
            .then(r => r.json())
            .then(data => {
                const select = document.getElementById('categoryParent');
                select.innerHTML = '<option value="">None</option>';
                data.parents.forEach(p => {
                    select.innerHTML += `<option value="${p.id}">${p.name}</option>`;
                });
            })
            .catch(err => console.error('Error loading parents:', err));
        }

        function deleteCategory(id) {
            if (!confirm('Are you sure?')) return;

            fetch(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(err => console.error('Error deleting category:', err));
        }

        document.getElementById('categoryForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('categoryId').value;
            const url = id ? `${baseUrl}/${id}` : baseUrl;
            const method = id ? 'PUT' : 'POST';

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            fetch(url, {
                method,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(err => console.error('Error saving category:', err));
        });

        // Close modal on outside click
        document.getElementById('categoryModal').addEventListener('click', (e) => {
            if (e.target.id === 'categoryModal') closeCategoryModal();
        });
    </script>
</x-x-admin-layout-modern>
