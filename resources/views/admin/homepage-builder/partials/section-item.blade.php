<div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors group" data-section-id="{{ $section->id }}">
    <!-- Left Section: Drag Handle + Info -->
    <div class="flex items-center gap-4 flex-1 min-w-0">
        <!-- Drag Handle -->
        <div class="drag-handle cursor-grab active:cursor-grabbing p-2 hover:bg-gray-100 rounded-lg transition-colors flex-shrink-0">
            <i class="fas fa-grip-vertical text-gray-400 group-hover:text-gray-600"></i>
        </div>

        <!-- Section Info -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-layer-group text-indigo-600"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-gray-900">{{ $section->title ?? ucfirst(str_replace('_', ' ', $section->section_type)) }}</p>
                    <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $section->section_type)) }} • {{ $section->created_at->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section: Status + Actions -->
    <div class="flex items-center gap-3 flex-shrink-0">
        <!-- Status Toggle -->
        <button type="button"
                onclick="toggleSectionStatus({{ $section->id }})"
                class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors {{ $section->status ? 'bg-green-500' : 'bg-gray-300' }}">
            <span class="inline-block h-6 w-6 transform rounded-full bg-white shadow-lg transition-transform {{ $section->status ? 'translate-x-7' : 'translate-x-1' }}"></span>
        </button>

        <!-- Config Button -->
        <button type="button"
                onclick="openConfigModal('{{ $section->section_type }}', {{ $section->id }})"
                class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors"
                title="Configure section settings">
            <i class="fas fa-cog"></i>
        </button>

        <!-- Edit Button -->
        <a href="#" onclick="editSection({{ $section->id }}); return false;" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
            <i class="fas fa-edit"></i>
        </a>

        <!-- Delete Button -->
        <button type="button"
                onclick="deleteSection({{ $section->id }})"
                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>
