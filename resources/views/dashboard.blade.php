<x-layouts.app :title="__('Dashboard')">
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
        <!-- Statistics Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold">Total Games</h3>
                <p class="text-2xl">{{ $totalGames }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold">Total Categories</h3>
                <p class="text-2xl">{{ $totalCategories }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
                <h3 class="text-lg font-semibold">Total Users</h3>
                <p class="text-2xl">{{ $totalUsers }}</p>
            </div>
        </div>
        <!-- Add Form -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold mb-4">Add New Game</h3>
            <form action="{{ route('games.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="block text-sm font-medium">Title</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-white dark:bg-gray-800">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="release_year" class="block text-sm font-medium">Release Year</label>
                        <input type="number" name="release_year" id="release_year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1900" max="{{ date('Y') }}">
                        @error('release_year') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Add Game</button>
            </form>
        </div>
        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h3 class="text-lg font-semibold mb-4">Games</h3>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Title</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Release Year</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($games as $game)
                        <tr>
                            <td class="px-4 py-2">{{ $game->title }}</td>
                            <td class="px-4 py-2">{{ $game->description }}</td>
                            <td class="px-4 py-2">{{ $game->release_year }}</td>
                            <td class="px-4 py-2">{{ $game->category ? $game->category->name : 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <button class="bg-yellow-500 text-white px-2 py-1 rounded mr-2" data-id="{{ $game->id }}" data-title="{{ $game->title }}" data-description="{{ $game->description }}" data-release_year="{{ $game->release_year }}" data-category_id="{{ $game->category_id }}" onclick="openEditModal(this)">Edit</button>
                                <form action="{{ route('games.destroy', $game) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this game?')">
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
                    <h3 class="text-lg font-semibold mb-4">Edit Game</h3>
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="editId">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editTitle" class="block text-sm font-medium">Title</label>
                                <input type="text" name="title" id="editTitle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="editReleaseYear" class="block text-sm font-medium">Release Year</label>
                                <input type="number" name="release_year" id="editReleaseYear" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="editDescription" class="block text-sm font-medium">Description</label>
                                <textarea name="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label for="editCategoryId" class="block text-sm font-medium">Category</label>
                                <select name="category_id" id="editCategoryId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
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
        document.getElementById('editTitle').value = button.dataset.title;
        document.getElementById('editDescription').value = button.dataset.description;
        document.getElementById('editReleaseYear').value = button.dataset.release_year;
        document.getElementById('editCategoryId').value = button.dataset.category_id;
        document.getElementById('editForm').action = '/games/' + button.dataset.id;
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
