<x-layouts.app :title="__('Add New Category')">
    @if(session('success'))
        <div id="successMessage" class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div id="errorMessage" class="bg-red-500 text-white p-4 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Add Form -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold mb-4">Add New Category</h3>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Add Category</button>
            </form>
        </div>
        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold mb-4">Categories</h3>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Games Count</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="px-4 py-2">{{ $category->name }}</td>
                            <td class="px-4 py-2">{{ $category->description }}</td>
                            <td class="px-4 py-2">{{ $category->games_count }} games</td>
                            <td class="px-4 py-2">
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded mr-2" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-description="{{ $category->description }}" onclick="openEditModal(this)">Edit</button>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded shadow-lg w-1/2">
                    <h3 class="text-lg font-semibold mb-4">Edit Category</h3>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="editId">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editName" class="block text-sm font-medium">Name</label>
                                <input type="text" name="name" id="editName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="editDescription" class="block text-sm font-medium">Description</label>
                                <textarea name="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
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
