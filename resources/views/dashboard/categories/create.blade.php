<x-layout>
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">Add New Category</h2>

        <form method="POST" action="{{ route('dashboard.categories.store') }}">
            @csrf

            <!-- Name Input -->
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 
                        block w-full p-2.5"
                    placeholder="Category name" required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color Dropdown (Tailwind Class) -->
            <div class="mb-4">
                <label for="color" class="block mb-2 text-sm font-medium text-gray-900">Color (Tailwind Class)</label>
                <select name="color" id="color" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 
                           block w-full p-2.5">
                    <option disabled selected value="">-- Select a color --</option>
                    <option value="bg-red-100 text-red-800" {{ old('color') == 'bg-red-100 text-red-800' ? 'selected' : '' }}>Red</option>
                    <option value="bg-orange-100 text-orange-800" {{ old('color') == 'bg-orange-100 text-orange-800' ? 'selected' : '' }}>Orange</option>
                    <option value="bg-yellow-100 text-yellow-800" {{ old('color') == 'bg-yellow-100 text-yellow-800' ? 'selected' : '' }}>Yellow</option>
                    <option value="bg-lime-100 text-lime-800" {{ old('color') == 'bg-lime-100 text-lime-800' ? 'selected' : '' }}>Lime</option>
                    <option value="bg-green-100 text-green-800" {{ old('color') == 'bg-green-100 text-green-800' ? 'selected' : '' }}>Green</option>
                    <option value="bg-emerald-100 text-emerald-800" {{ old('color') == 'bg-emerald-100 text-emerald-800' ? 'selected' : '' }}>Emerald</option>
                    <option value="bg-teal-100 text-teal-800" {{ old('color') == 'bg-teal-100 text-teal-800' ? 'selected' : '' }}>Teal</option>
                    <option value="bg-cyan-100 text-cyan-800" {{ old('color') == 'bg-cyan-100 text-cyan-800' ? 'selected' : '' }}>Cyan</option>
                    <option value="bg-sky-100 text-sky-800" {{ old('color') == 'bg-sky-100 text-sky-800' ? 'selected' : '' }}>Sky</option>
                    <option value="bg-blue-100 text-blue-800" {{ old('color') == 'bg-blue-100 text-blue-800' ? 'selected' : '' }}>Blue</option>
                    <option value="bg-indigo-100 text-indigo-800" {{ old('color') == 'bg-indigo-100 text-indigo-800' ? 'selected' : '' }}>Indigo</option>
                    <option value="bg-violet-100 text-violet-800" {{ old('color') == 'bg-violet-100 text-violet-800' ? 'selected' : '' }}>Violet</option>
                    <option value="bg-purple-100 text-purple-800" {{ old('color') == 'bg-purple-100 text-purple-800' ? 'selected' : '' }}>Purple</option>
                    <option value="bg-fuchsia-100 text-fuchsia-800" {{ old('color') == 'bg-fuchsia-100 text-fuchsia-800' ? 'selected' : '' }}>Fuchsia</option>
                    <option value="bg-pink-100 text-pink-800" {{ old('color') == 'bg-pink-100 text-pink-800' ? 'selected' : '' }}>Pink</option>
                    <option value="bg-rose-100 text-rose-800" {{ old('color') == 'bg-rose-100 text-rose-800' ? 'selected' : '' }}>Rose</option>
                    <option value="bg-gray-100 text-gray-800" {{ old('color') == 'bg-gray-100 text-gray-800' ? 'selected' : '' }}>Gray</option>
                    <option value="bg-zinc-100 text-zinc-800" {{ old('color') == 'bg-zinc-100 text-zinc-800' ? 'selected' : '' }}>Zinc</option>
                    <option value="bg-neutral-100 text-neutral-800" {{ old('color') == 'bg-neutral-100 text-neutral-800' ? 'selected' : '' }}>Neutral</option>
                    <option value="bg-stone-100 text-stone-800" {{ old('color') == 'bg-stone-100 text-stone-800' ? 'selected' : '' }}>Stone</option>
                </select>
                @error('color')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="bg-primary-700 hover:bg-primary-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">
                Save Category
            </button>
        </form>
    </div>
</x-layout>
