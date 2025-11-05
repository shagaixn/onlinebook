<!-- FOOTER -->
<footer class="bg-white border-t border-gray-200 mt-20">
  <div class="max-w-7xl mx-auto px-8 py-12 grid grid-cols-1 md:grid-cols-5 gap-10 text-sm text-gray-600">

    <!-- Column 1: Logo & description -->
    <div class="md:col-span-1">
      <div class="flex items-center mb-3">
        <span class="text-2xl font-bold text-blue-600">M</span>
        <span class="text-xl font-semibold">book</span>
      </div>
      <p class="text-gray-500 mb-2">Цахим ном, Аудио ном, Подкастын цогц платформ юм.</p>
      <p class="font-semibold text-gray-800">Мэдрэмж, Мэдлэгийг өнгөлнө</p>
    </div>

    <!-- Column 2: Бүтээл нийтлэх -->
    <div>
  <h3 class="font-semibold text-gray-800 mb-3">Бүтээл нийтлэх</h3>
  @if(auth()->check() && auth()->user()->role === 'admin')
      <a href="{{ route('admin.dashboard') }}"
         class="flex items-center justify-center gap-2 bg-blue-600 text-white py-2 px-5 rounded-lg hover:bg-blue-700 transition-all"
         style="text-decoration: none;">
          <span>Бүтээл нийтлэх</span>
          <span class="text-lg font-bold">+</span>
      </a>
  @else
      <button
          class="flex items-center justify-center gap-2 bg-blue-300 text-white py-2 px-5 rounded-lg opacity-60 cursor-not-allowed"
          type="button"
          disabled
      >
          <span>Бүтээл нийтлэх</span>
          <span class="text-lg font-bold">+</span>
      </button>
  @endif
  <p class="mt-2 text-gray-500">Таны нийтэлсэн бүтээлийг уншигч, сонсогчдод хил хязгааргүй хүргэнэ</p>
</div>  

    <!-- Column 3: Бидний тухай -->
    <div>
      <h3 class="font-semibold text-gray-800 mb-3">Бидний тухай</h3>
      <ul class="space-y-2">
        <li><a href="#" class="hover:text-blue-600">Танилцуулга</a></li>
        <li><a href="#" class="hover:text-blue-600">Хамтран ажиллах</a></li>
      </ul>
    </div>

    <!-- Column 4: Тусламж -->
    <div>
      <h3 class="font-semibold text-gray-800 mb-3"><span class="text-blue-600">Тусламж</span></h3>
      <ul class="space-y-2">
        <li><a href="#" class="hover:text-blue-600">Түгээмэл асуултууд</a></li>
        <li><a href="#" class="hover:text-blue-600">Хэрэглэх заавар</a></li>
        <li><a href="#" class="hover:text-blue-600">Худалдан авалт</a></li>
        <li><a href="#" class="hover:text-blue-600">Карт холбох</a></li>
        <li><a href="#" class="hover:text-blue-600">Лого татах</a></li>
      </ul>
    </div>

    <!-- Column 5: Холбоо барих -->
    <div>
      <h3 class="font-semibold text-gray-800 mb-3"><span class="text-blue-600">Холбоо барих</span></h3>
      <p class="font-medium text-gray-700 mb-1">"М нэрмэх" ХХК</p>
      <p><span class="text-gray-500">Утас:</span> Unkown</p>
      <p><span class="text-gray-500">И-мэйл:</span> <a href="mailto:support@m-book.mn" class="text-blue-600 hover:underline">support@m-book.mn</a></p>

      <div class="mt-3">
        <button class="flex items-center gap-2 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          <span>Промо код</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Bottom social media row -->
  <div class="border-t border-gray-100 py-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between px-8">
      <p class="text-gray-500 text-sm mb-3 md:mb-0">Биднийг сошиал сүлжээнд дагаарай</p>
      <div class="flex space-x-4">
        <a href="https://www.facebook.com/" class="text-gray-500 hover:text-blue-600 text-xl"><i class="fab fa-facebook"></i></a>
        <a href="https://www.instagram.com/" class="text-gray-500 hover:text-pink-500 text-xl"><i class="fab fa-instagram"></i></a>
        <a href="#" class="text-gray-500 hover:text-red-600 text-xl"><i class="fab fa-youtube"></i></a>
        <a href="#" class="text-gray-500 hover:text-blue-700 text-xl"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>
  </div>
</footer>