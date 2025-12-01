<x-layouts.app :title="__('Add New Category')">
    @if(session('success'))
        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Add Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Add New Category</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                        @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors">Add Category</button>
            </form>
        </div>
        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Categories</h3>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Games Count</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">{{ $category->description }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $category->games_count }} games</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-3 py-1 rounded-md text-xs font-medium transition-colors" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}" onclick="openEditModal(this)">Edit</button>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-black px-3 py-1 rounded-md text-xs font-medium transition-colors">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white text-black p-6 rounded shadow-lg w-1/2">
                    <h3 class="text-lg font-semibold mb-4">Edit Category</h3>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="editId">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editName" class="block text-sm font-medium text-black">Name</label>
                                <input type="text" name="name" id="editName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="editDescription" class="block text-sm font-medium text-black">Description</label>
                                <textarea name="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-black"></textarea>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">Update</button>
                            <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openEditModal(button) {
            document.getElementById('editId').value = button.dataset.id;
            document.getElementById('editName').value = button.dataset.name;
            document.getElementById('editDescription').value = button.dataset.description;
            document.getElementById('editForm').action = '/categories/' + button.dataset.id;
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        window.onload = function() {
            const successEl = document.getElementById('successMessage');
            const errorEl = document.getElementById('errorMessage');
            if (successEl) {
                setTimeout(() => { 
                    successEl.style.transition = "opacity 1s ease-out";
                    successEl.style.opacity = '0';
                    setTimeout(() => { successEl.style.display = 'none'; }, 1000);
                }, 5000);
            }
            if (errorEl) {
                setTimeout(() => { 
                    errorEl.style.transition = "opacity 1s ease-out";
                    errorEl.style.opacity = '0';
                    setTimeout(() => { errorEl.style.display = 'none'; }, 1000);
                }, 5000);
            }
        }
    </script>
</x-layouts.app>
