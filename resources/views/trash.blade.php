<x-layouts.app :title="__('Trash')">
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
        <div class="grid auto-rows-min gap-6 md:grid-cols-2">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trashed Games</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ $trashedGames->count() }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Trashed Categories</h3>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $trashedCategories->count() }}</p>
            </div>
        </div>
        <!-- Trashed Categories Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Trashed Categories</h3>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($trashedCategories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">{{ $category->description }}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <form action="{{ route('categories.restore', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">Restore</button>
                                    </form>
                                    <form action="{{ route('categories.forceDelete', $category) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">Delete Forever</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Trashed Games Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
            <h3 class="text-xl font-semibold mb-6 text-gray-900 dark:text-white">Trashed Games</h3>
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
                        @foreach($trashedGames as $game)
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
                                    <form action="{{ route('games.restore', $game) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">Restore</button>
                                    </form>
                                    <form action="{{ route('games.forceDelete', $game) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this game?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors">Delete Forever</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<script>
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
