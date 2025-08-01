@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    {!! __('Showing') !!}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            {{-- Navigasi Halaman --}}
            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Tombol Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-l-md">
                            &laquo;
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:text-gray-500">
                            &laquo;
                        </a>
                    @endif

                    {{-- Logika Penentuan Nomor Halaman --}}
                    @php
                        $currentPage = $paginator->currentPage();
                        $lastPage = $paginator->lastPage();
                        $pages = [];

                        // Selalu tampilkan halaman pertama
                        $pages[] = 1;

                        // Jika pengguna ada di halaman 2 atau 3, tambahkan halaman tersebut
                        if ($currentPage == 2) {
                            $pages[] = 2;
                            $pages[] = 3;
                        } elseif ($currentPage == 3) {
                            $pages[] = 2;
                            $pages[] = 3;
                            $pages[] = 4;
                        } elseif ($currentPage > 3) {
                            $pages[] = '...';
                            $pages[] = $currentPage - 1;
                        }

                        // Tambahkan halaman aktif
                        if ($currentPage > 1 && $currentPage < $lastPage) {
                            $pages[] = $currentPage;
                        }

                        // Tambahkan halaman setelahnya jika tidak melewati batas akhir
                        if ($currentPage + 1 < $lastPage) {
                            $pages[] = $currentPage + 1;
                        }

                        // Tambahkan "..." sebelum halaman terakhir jika masih jauh
                        if ($currentPage < $lastPage - 2) {
                            $pages[] = '...';
                        }

                        // Selalu tampilkan halaman terakhir jika lebih dari 1 halaman
                        if ($lastPage > 1) {
                            $pages[] = $lastPage;
                        }

                        // Pastikan tidak ada duplikasi angka
                        $pages = array_values(array_unique($pages));
                    @endphp

                    {{-- Tampilkan Nomor Halaman --}}
                    @foreach ($pages as $page)
                        @if ($page == '...')
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default">
                                ...
                            </span>
                        @elseif ($page == $paginator->currentPage())
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 cursor-default rounded-md">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $paginator->url($page) }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:text-gray-500">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Tombol Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:text-gray-500">
                            &raquo;
                        </a>
                    @else
                        <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-r-md">
                            &raquo;
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
