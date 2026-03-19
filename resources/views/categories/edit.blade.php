<x-layout>
    <div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Jenis</h2>

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Jenis<span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ $category->name }}" class="w-full border rounded px-3 py-2 focus:outline-blue-500" required>
                    @error('name') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2 focus:outline-blue-500">{{ $category->description }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <a href="{{ route('categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</x-layout>