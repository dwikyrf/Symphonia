<section class="bg-white py-8 md:py-16"> {{-- Latar belakang diatur hanya putih --}}
    <div class="max-w-screen-xl mx-auto px-4 2xl:px-0">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews</h2>

        {{-- Daftar Ulasan --}}
        <div class="space-y-6 mb-12">
            @forelse($product->reviews as $review)
                <div class="border-b pb-6 border-gray-200">
                    <div class="flex items-center mb-2">
                        <div class="flex items-center mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.11 3.423a1 1 0 00.95.69h3.6c.969 0 1.371 1.24.588 1.81l-2.92 2.12a1 1 0 00-.364 1.118l1.11 3.422c.3.922-.755 1.688-1.538 1.118L10 13.347l-2.92 2.12c-.783.57-1.838-.196-1.539-1.118l1.11-3.422a1 1 0 00-.364-1.118l-2.92-2.12c-.783-.57-.38-1.81.588-1.81h3.6a1 1 0 00.95-.69l1.11-3.423z" />
                                </svg>
                            @endfor
                        </div>
                        @if($review->verified)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full ml-2">Verified Purchase</span>
                        @endif
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">{{ $review->title }}</h4>
                    <p class="text-sm text-gray-500">{{ $review->created_at->format('F j, Y') }} oleh {{ $review->user->name ?? 'Pengguna' }}</p>
                    <p class="mt-2 text-gray-700">{{ $review->content }}</p>
                    @if($review->images && count(json_decode($review->images, true)) > 0)
                        <div class="mt-4 flex flex-wrap gap-3">
                            {{-- @foreach(json_decode($review->images, true) as $imagePath)
                                <img src="{{ asset('storage/' . $imagePath) }}" 
                                     class="review-image w-20 h-20 object-cover rounded-md border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200" 
                                     alt="Review image for {{ $product->name }}"
                                     style="cursor: zoom-in;">
                            @endforeach --}}
                            @foreach(json_decode($review->images, true) as $imagePath)
                                <img src="{{ Storage::disk('public')->url($imagePath) }}"
                                    class="review-image w-20 h-20 object-cover rounded-md border
                                            border-gray-200 shadow-sm hover:shadow-md transition-shadow"
                                    alt="Review image for {{ $product->name }}">
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>

        {{-- Form Ulasan --}}
        {{-- Pastikan variabel $hasPurchased sudah didefinisikan di controller Anda --}}
        {{-- @if(auth()->check() && ($hasPurchased ?? false))
            @include('reviews.form', ['product' => $product])
        @elseif(auth()->check())
            <p class="text-sm text-gray-500 italic">Anda harus membeli produk ini terlebih dahulu untuk memberikan ulasan.</p>
        @else
            <p class="text-sm text-gray-500 italic"><a href="{{ route('login') }}" class="text-primary-600 hover:underline">Login</a> atau <a href="{{ route('register') }}" class="text-primary-600 hover:underline">daftar</a> untuk memberikan ulasan setelah pembelian.</p>
        @endif --}}
    </div>
    {{-- … section Customer Reviews di atas … --}}

<script>
document.addEventListener('click', function (e) {
  const img = e.target;                    // elemen yang diklik
  if (!img.classList.contains('review-image')) return; // hanya thumbnail review

  /* === overlay gelap === */
  const overlay = document.createElement('div');
  overlay.style.cssText = `
    position:fixed; inset:0; z-index:9999;
    background:rgba(0,0,0,.85);
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .3s ease;
  `;

  /* === gambar besar === */
  const full = document.createElement('img');
  full.src = img.src;
  full.style.cssText = `
    max-width:90%; max-height:90%;
    border-radius:8px; box-shadow:0 10px 25px rgba(0,0,0,.5);
    transform:scale(.9); transition:transform .3s ease;
  `;

  overlay.appendChild(full);
  document.body.appendChild(overlay);
  document.body.style.overflow = 'hidden';

  // animasi muncul
  requestAnimationFrame(() => {
    overlay.style.opacity = '1';
    full.style.transform = 'scale(1)';
  });

  // tutup saat overlay diklik
  overlay.addEventListener('click', () => {
    overlay.style.opacity = '0';
    full.style.transform = 'scale(.9)';
    setTimeout(() => {
      overlay.remove();
      document.body.style.overflow = '';
    }, 300);
  });
});
</script>

</section>