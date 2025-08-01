<div x-data="{ open: false }">
    <!-- Tombol Toggle Sidebar -->
    <button @click="open = !open" type="button" 
        class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
        </svg>
    </button>

    <!-- Sidebar -->
    <aside>
        <!-- Sidebar Overlay (Mobile) -->
        <div x-show="open"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm z-40"
            x-transition.opacity>
            <!-- Sidebar Container -->
            <div class="bg-white w-3/4 md:w-64 p-6 rounded-lg shadow-lg relative mx-auto my-20"
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="translate-x-full opacity-0"
                x-transition:enter-end="translate-x-0 opacity-100"
                x-transition:leave="transition transform ease-in duration-300"
                x-transition:leave-start="translate-x-0 opacity-100"
                x-transition:leave-end="translate-x-full opacity-0">
                
                <!-- Tombol Close -->
                <button @click="open = false"
                    class="absolute top-2 right-2 text-gray-700 hover:text-gray-900">
                    ✖
                </button>
      
                <!-- Judul Sidebar -->
                <h2 class="text-2xl font-bold text-center mb-4">Menu</h2>
      
                <!-- Navigasi Sidebar -->
                <nav>
                    <div class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200">
                      <ul class="space-y-2">
                          <li>
                              <a href="/dashboard" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group">
                                  <svg aria-hidden="true" class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
                                  <span class="ml-3">Overview</span>
                              </a>
                          </li>
                          <li>
                              <button type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100" aria-controls="dropdown-pages" data-collapse-toggle="dropdown-pages">
                                  <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                                  <span class="flex-1 ml-3 text-left whitespace-nowrap">Pages</span>
                                  <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                              </button>
                              <ul id="dropdown-pages" class="hidden py-2 space-y-2">
                                  <li>
                                      <a href="/dashboard/products" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 ">Katalog Produk</a>
                                  </li>
                                  <li>
                                      <a href="/dashboard/payment" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">Payment</a>
                                  </li>
                                  <li>
                                      <a href="/dashboard/transactions" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">Pesanan</a>
                                  </li>
                              </ul>
                          </li>
                          <li>
                              <button type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100" aria-controls="dropdown-sales" data-collapse-toggle="dropdown-sales">
                                  <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
                                  <span class="flex-1 ml-3 text-left whitespace-nowrap">Sales</span>
                                  <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                              </button>
                              <ul id="dropdown-sales" class="hidden py-2 space-y-2">
                                      <li>
                                          <a href="/dashboard/products" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">Products</a>
                                      </li>
                                      <li>
                                          <a href="#" class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">Invoice</a>
                                      </li>
                              </ul>
                          </li>
                          <li>
                              <a href="#" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group">
                                  <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path><path d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path></svg>
                                  <span class="flex-1 ml-3 whitespace-nowrap">Messages</span>
                                  <span class="inline-flex justify-center items-center w-5 h-5 text-xs font-semibold rounded-full text-primary-800 bg-primary-100">
                                      6   
                                  </span>
                              </a>
                          </li>
                      </ul>
                      <ul class="pt-5 mt-5 space-y-2 border-t border-gray-200">
                          <li>
                           <a href="#" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 group" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                             <svg class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
                             </svg>
                               <span class="ml-3">Sign Out</span>
                           </a>
                         </li>
                      </ul>
                  </div>
                  </nav>
            </div>
        </div>
      
        <!-- Sidebar Desktop -->
        <div class="hidden md:block bg-white shadow-md w-64 min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
            <nav>
              <div class="overflow-y-auto py-5 px-3 h-full bg-white border-r border-gray-200">
                <ul class="space-y-2">
                    <li>
                        <a href="/dashboard" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg hover:bg-gray-100 group">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                            </svg>
                            <span class="ml-3">Overview</span>
                        </a>
                    </li>
    
                    <!-- Dropdown Pages -->
                    <li x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Pages</span>
                            <svg aria-hidden="true" class="w-6 h-6 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul x-show="open" class="py-2 space-y-2 pl-6">
                            <li><a href="{{ route('dashboard.products.index') }}" class="block px-4 py-2 text-gray-900 rounded-lg hover:bg-gray-100">Products</a></li>
                            <li><a href="{{ route('dashboard.categories.index') }}" class="block px-4 py-2 text-gray-900 rounded-lg hover:bg-gray-100">Category</a></li>
                        </ul>
                    </li>
    
                    <!-- Dropdown Sales -->
                    <li x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Sales</span>
                            <svg aria-hidden="true" class="w-6 h-6 transition-transform duration-200" :class="{'rotate-180': open}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul x-show="open" class="py-2 space-y-2 pl-6">
                            <li><a href="{{ route('dashboard.sales.index') }}" class="block px-4 py-2 text-gray-900 rounded-lg hover:bg-gray-100">Sales Report</a></li>
                            <li><a href="{{ route('dashboard.order.index') }}" class="block px-4 py-2 text-gray-900 rounded-lg hover:bg-gray-100">Order</a></li>
                            <li><a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-gray-900 rounded-lg hover:bg-gray-100">Transaction</a></li>
                            <li><a href="{{ route('dashboard.shipping.index') }}" class="block px-4 py-2 text-gray-900 rounded-lg hover:bg-gray-100">Shipping</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="pt-5 mt-5 space-y-2 border-t border-gray-200">
                    <li>
                     <a href="#" class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg transition duration-75 hover:bg-gray-100 group" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                       <svg class="flex-shrink-0 w-6 h-6 text-gray-400 transition duration-75 group-hover:text-gray-900" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                         <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
                       </svg>
                         <span class="ml-3">Sign Out</span>
                     </a>
                   </li>
                </ul>
            </div>
            </nav>
        </div>
    </aside>
</div>

