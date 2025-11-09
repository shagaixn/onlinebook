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
        <h1 class="text-4xl font-bold mb-4 tracking-tight">
            <span class="text-blue-600 drop-shadow-sm">#Мэдрэмж</span>, Мэдлэгийг өнгөлнө.
        </h1>

        <p class="text-gray-400 max-w-xl mb-8">
            Хил хязгаар, цаг хугацааг үл харгалзан бүгдэд нээлттэйгээр мэдрэмж, мэдлэгийг түгээх Mbook-т тавтай морилно уу!
        </p>
        <button class="bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold py-3 px-10 rounded-full shadow-lg hover:from-blue-500 hover:to-blue-700 transition">
            Subscription
        </button>

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
  const testimonials = document.querySelectorAll('.testimonial');
  let currentIndex = 0;

  function showTestimonial(index) {
    testimonials.forEach((testimonial, i) => {
      testimonial.classList.toggle('active', i === index);
    });
  }

  document.getElementById('prev').addEventListener('click', () => {
    currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
    showTestimonial(currentIndex);
  });

  document.getElementById('next').addEventListener('click', () => {
    currentIndex = (currentIndex + 1) % testimonials.length;
    showTestimonial(currentIndex);
  });
</script>

    @include('include.footer')

<!-- Font Awesome for social icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>


</body>
</html>
