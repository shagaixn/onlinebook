@include('include.header')


@section('content')
<div class="max-w-2xl mx-auto mt-16 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Тавтай морил!</h2>
    <p>Та амжилттай Нэвтэрлээ!</p>
</div>
@endsection
<style>@keyframes marquee {
  0% { transform: translateX(0%);}
  100% { transform: translateX(-50%);}
}

.animate-marquee {
  display: flex;
  width: max-content;
  animation: marquee 20s linear infinite;
}</style>

    <!-- Main Section -->
    <main class="flex flex-col items-center justify-center text-center py-20 px-4">
      <!-- Add the image at the top of the body section -->

        <h1 class="text-4xl font-bold mb-4 tracking-tight">
            <span class="text-blue-600 drop-shadow-sm">#Мэдрэмж</span>, Мэдлэгийг өнгөлнө.
        </h1>

        <p class="text-gray-400 max-w-xl mb-8">
            Хил хязгаар, цаг хугацааг үл харгалзан бүгдэд нээлттэйгээр мэдрэмж, мэдлэгийг түгээх Mbook-т тавтай морилно уу!
        </p>
        <button class="bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold py-3 px-10 rounded-full shadow-lg hover:from-blue-500 hover:to-blue-700 transition">
            Subscription
        </button>

        <!-- Шинэ номнууд -->
        <section class="w-full max-w-5xl mx-auto mt-16">
          <h2 class="text-2xl font-bold mb-6 text-left">Шинээр нэмэгдсэн номнууд</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($newBooks ?? [] as $book)
              <div class="bg-white dark:bg-slate-900 shadow rounded-xl overflow-hidden">
                @if($book->cover_image)
                  <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                  <h3 class="text-lg font-semibold mb-1">{{ $book->title }}</h3>
                  <p class="text-sm text-gray-500 mb-2">Зохиолч: {{ $book->author->name ?? '-' }}</p>
                  <a href="{{ route('books.show', $book->id) }}" class="text-blue-600 hover:underline">Дэлгэрэнгүй</a>
                </div>
              </div>
            @endforeach
            @if(empty($newBooks) || count($newBooks) === 0)
              <div class="col-span-3 text-center text-gray-400">Шинэ ном алга байна.</div>
            @endif
          </div>
        </section>

        <!-- Шинэ зохиолчид -->
        <section class="w-full max-w-5xl mx-auto mt-16">
          <h2 class="text-2xl font-bold mb-6 text-left">Шинээр нэмэгдсэн зохиолчид</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($newAuthors ?? [] as $author)
              <div class="bg-white dark:bg-slate-900 shadow rounded-xl overflow-hidden">
                @if($author->profile_image)
                  <img src="{{ asset('storage/' . $author->profile_image) }}" alt="{{ $author->name }}" class="w-full h-48 object-cover">
                @endif
                <div class="p-4">
                  <h3 class="text-lg font-semibold mb-1">{{ $author->name }}</h3>
                  <p class="text-sm text-gray-500 mb-2">{{ $author->nationality ?? '' }}</p>
                  <a href="{{ route('authors.show', $author->slug) }}" class="text-blue-600 hover:underline">Дэлгэрэнгүй</a>
                </div>
              </div>
            @endforeach
            @if(empty($newAuthors) || count($newAuthors) === 0)
              <div class="col-span-3 text-center text-gray-400">Шинэ зохиолч алга байна.</div>
            @endif
          </div>
        </section>

        <!-- Scroll Circles (Books) -->
        <div class="relative mt-20 w-full max-w-3xl h-80">
            <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-60 h-60 rounded-full border border-blue-200"></div>
            <div class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full border border-blue-200"></div>

            <!-- Book covers -->
            <img src="book1.png" alt="Book 1" class="absolute w-24 h-24 rounded-full top-10 left-1/4 object-cover">
            <img src="book2.png" alt="Book 2" class="absolute w-24 h-24 rounded-full top-10 right-1/4 object-cover">
            <img src="book3.png" alt="Book 3" class="absolute w-24 h-24 rounded-full bottom-10 left-1/2 transform -translate-x-1/2 object-cover">
        </div>

        <p class="mt-6 text-gray-400 flex items-center gap-2">
            <span class="material-icons">mouse</span> scroll
        </p>
    </main>
    <section id="testimonials">
      

  <div class="relative w-full overflow-hidden bg-gray-900 py-6">
  <div class="animate-marquee whitespace-nowrap flex">
    <span class="mx-8 text-2xl text-white font-bold">📚 Book 1</span>
    <span class="mx-8 text-2xl text-white font-bold">📕 Book 2</span>
    <span class="mx-8 text-2xl text-white font-bold">📖 Book 3</span>
    <span class="mx-8 text-2xl text-white font-bold">📙 Book 4</span>
    <span class="mx-8 text-2xl text-white font-bold">📘 Book 5</span>
    <!-- Дахин эхлэх үгнүүдийг давхардуулж бичих -->
    <span class="mx-8 text-2xl text-white font-bold">📚 Book 1</span>
    <span class="mx-8 text-2xl text-white font-bold">📕 Book 2</span>
    <span class="mx-8 text-2xl text-white font-bold">📖 Book 3</span>
    <span class="mx-8 text-2xl text-white font-bold">📙 Book 4</span>
    <span class="mx-8 text-2xl text-white font-bold">📘 Book 5</span>
  </div>
</div>
</section>
<script>
  // Improved JS for testimonials
  document.addEventListener('DOMContentLoaded', function() {
    const testimonials = document.querySelectorAll('.testimonial');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    let currentIndex = 0;
    let autoRotate;

    function showTestimonial(index) {
      testimonials.forEach((testimonial, i) => {
        testimonial.classList.toggle('active', i === index);
      });
    }

    function rotateTestimonials() {
      if (testimonials.length > 0) {
        currentIndex = (currentIndex + 1) % testimonials.length;
        showTestimonial(currentIndex);
      }
    }

    if (testimonials.length > 0) {
      showTestimonial(currentIndex);
      autoRotate = setInterval(rotateTestimonials, 5000); // auto-rotate every 5s
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        if (testimonials.length > 0) {
          currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
          showTestimonial(currentIndex);
        }
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        if (testimonials.length > 0) {
          currentIndex = (currentIndex + 1) % testimonials.length;
          showTestimonial(currentIndex);
        }
      });
    }
  });
</script>

    @include('include.footer')

<!-- Font Awesome for social icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


</body>
</html>
