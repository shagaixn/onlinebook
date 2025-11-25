const slidesTrack = document.querySelector('.slides-track');
const slides = document.querySelectorAll('.portfolio-slide');
const prevBtn = document.querySelector('.slider-arrow.prev');
const nextBtn = document.querySelector('.slider-arrow.next');
const progressBar = document.querySelector('.slider-progress .bar');

let currentSlide = 0;

function updateSlider() {
    const percent = ((currentSlide + 1) / slides.length) * 100;
    // Move slides
    slidesTrack.style.transform = `translateX(-${currentSlide * 100}%)`;
    // Progress bar
    if (progressBar) progressBar.style.width = percent + "%";
    // Disable/enable arrows
    prevBtn.disabled = currentSlide === 0;
    nextBtn.disabled = currentSlide === slides.length - 1;
    // Fade out effect
    slides.forEach((slide, i) => {
        slide.classList.toggle('is-fading-out', i !== currentSlide);
    });
}

prevBtn.addEventListener('click', () => {
    if (currentSlide > 0) {
        currentSlide--;
        updateSlider();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentSlide < slides.length - 1) {
        currentSlide++;
        updateSlider();
    }
});

updateSlider();