<x-layouts.app :title="__('Dashboard')">
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
        <!-- Statistics Cards -->
        <div class="grid auto-rows-min gap-6 md:grid-cols-3">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Games</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalGames }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Categories</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalCategories }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Total Users</h3>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalUsers }}</p>
            </div>
        </div>
        <!-- Add Form -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Add New Game</h3>
            <form action="{{ route('games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input type="text" name="title" id="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        @error('title') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="release_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Release Year</label>
                        <input type="number" name="release_year" id="release_year" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required min="1900" max="{{ date('Y') }}">
                        @error('release_year') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="3" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                        @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo (JPG/PNG, max 2MB)</label>
                        <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        @error('photo') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <button type="submit" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors">Add Game</button>
            </form>
        </div>
        <!-- Search & Filter -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Search & Filter</h3>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col md:flex-row gap-4 mb-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                <select name="category_id" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">Search</button>
                <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors">Clear</a>
                <a href="{{ route('games.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors">Export to PDF</a>
            </form>
        </div>
        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Games</h3>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Photo</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Release Year</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($games as $game)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($game->photo)
                                        <img src="{{ asset('storage/' . $game->photo) }}" alt="Game Photo" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                                            {{ strtoupper(substr($game->title, 0, 1)) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $game->title }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">{{ $game->description }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $game->release_year }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $game->category ? $game->category->name : 'N/A' }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button class="bg-yellow-500 hover:bg-yellow-600 text-black px-3 py-1 rounded-md text-xs font-medium transition-colors" data-id="{{ $game->id }}" data-title="{{ $game->title }}" data-description="{{ $game->description }}" data-release_year="{{ $game->release_year }}" data-category_id="{{ $game->category_id }}" onclick="openEditModal(this)">Edit</button>
                                    <form action="{{ route('games.destroy', $game) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this game?')">
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
                <div class="bg-white p-6 rounded shadow-lg w-1/2">
                    <h3 class="text-lg font-semibold mb-4 text-black">Edit Game</h3>
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="editId">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="editTitle" class="block text-sm font-medium text-black">Title</label>
                                <input type="text" name="title" id="editTitle" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div>
                                <label for="editReleaseYear" class="block text-sm font-medium text-black">Release Year</label>
                                <input type="number" name="release_year" id="editReleaseYear" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label for="editDescription" class="block text-sm font-medium text-black">Description</label>
                                <textarea name="description" id="editDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                            <div>
                                <label for="editCategoryId" class="block text-sm font-medium text-black">Category</label>
                                <select name="category_id" id="editCategoryId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="editPhoto" class="block text-sm font-medium text-black">Photo</label>
                                <input type="file" name="photo" id="editPhoto" accept="image/*" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <p class="text-xs text-gray-500 mt-1">JPG/PNG only, max 2MB</p>
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
        document.getElementById('editPhoto').value = ''; // Reset file input
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
