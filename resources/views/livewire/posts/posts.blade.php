<div class="p-4 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4">
        <h2 class="text-2xl font-bold mb-2 sm:mb-0">Posts</h2>
        <button wire:click="create" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
            wire:loading.attr="disabled">
            New Post
        </button>
    </div>

    @if(session('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded dark:bg-green-900 dark:text-green-300">
            {{ session('message') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="py-2 px-4 text-left">Title</th>
                    <th class="py-2 px-4 text-left">Author</th>
                    <th class="py-2 px-4 text-left">Status</th>
                    <th class="py-2 px-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr wire:key="post-{{ $post->id }}" class="border-t dark:border-gray-600">
                        <td class="py-2 px-4">{{ $post->title }}</td>
                        <td class="py-2 px-4">{{ $post->user->name }}</td>
                        <td class="py-2 px-4">
                            <span
                                class="{{ $post->is_published ? 'text-green-500 dark:text-green-400' : 'text-yellow-500 dark:text-yellow-400' }}">
                                {{ $post->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="py-2 px-4 space-x-2">
                            <button wire:click="edit({{ $post->id }})"
                                class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                                wire:loading.attr="disabled">
                                Edit
                            </button>
                            <button wire:click="confirmDelete('{{ $post->id }}')"
                                class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                wire:loading.attr="disabled">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $posts->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-2xl mx-4 dark:bg-gray-800">
                <h3 class="text-xl font-bold mb-4">{{ $modalTitle }}</h3>

                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Title</label>
                        <input type="text"
                            class="w-full p-2 border rounded focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500"
                            wire:model="postData.title">
                        @error('postData.title')
                            <span class="text-red-500 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 font-medium">Content</label>
                        <textarea
                            class="w-full p-2 border rounded focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-500"
                            wire:model="postData.content" rows="5"></textarea>
                        @error('postData.content')
                            <span class="text-red-500 dark:text-red-400">{{ $message ?? ''}}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" class="mr-2" wire:model="postData.is_published">
                            Publish
                        </label>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <button type="button"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition dark:bg-gray-700 dark:hover:bg-gray-600"
                            wire:click="cancel">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition dark:bg-blue-700 dark:hover:bg-blue-600"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Save</span>
                            <span wire:loading>Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation -->
    @if($confirmationPostId)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg w-full max-w-md mx-4 dark:bg-gray-800">
                <h3 class="text-xl font-bold mb-4">Confirm Deletion</h3>
                <p class="mb-4">Are you sure you want to delete this post?</p>
                <div class="flex justify-end space-x-2">
                    <button
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition dark:bg-gray-700 dark:hover:bg-gray-600"
                        wire:click="cancel">
                        Cancel
                    </button>
                    <button
                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition dark:bg-red-700 dark:hover:bg-red-600"
                        wire:click="delete">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
