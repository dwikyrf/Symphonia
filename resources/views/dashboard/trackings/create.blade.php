<x-layout>
    <x-slot:title>Add New Tracking Status</x-slot:title>

    <section class="py-8">
        <div class="max-w-lg mx-auto px-4">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Create Tracking Status</h2>

            <form action="{{ route('trackings.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 font-medium">Name</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Slug</label>
                    <input type="text" name="slug" class="w-full border rounded px-3 py-2" required>
                </div>

                <div>
                    <label class="block mb-2 font-medium">Color Class</label>
                    <input type="text" name="color" placeholder="ex: bg-green-500 text-white" class="w-full border rounded px-3 py-2" required>
                </div>

                <button type="submit" class="bg-primary-700 text-white px-4 py-2 rounded hover:bg-primary-800">
                    Save
                </button>
            </form>
        </div>
    </section>
</x-layout>
