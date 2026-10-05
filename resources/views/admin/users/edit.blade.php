<x-admin.layout-modern>
    <x-slot name="header">
        Edit User: {{ $user->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
                    <input type="text" name="name" id="name"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password (Leave blank to keep
                        current)</label>
                    <input type="password" name="password" id="password"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label for="role" class="block text-gray-700 font-bold mb-2">Role</label>
                    <select name="role" id="role"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">Select Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 col-span-2">
                    <label for="bio" class="block text-gray-700 font-bold mb-2">Bio</label>
                    <textarea name="bio" id="bio" rows="3"
                        class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4 col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        <span class="ml-2 font-bold text-gray-700">Active Account</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.users.index') }}"
                    class="mr-3 px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update
                    User</button>
            </div>
        </form>
    </div>
    </x-admin.layout-modern>
