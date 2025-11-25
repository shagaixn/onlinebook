@include('include.header')
<link rel="stylesheet" href="{{ asset('css/book-reader.css') }}">

<section class="portfolio-slider max-w-6xl mx-auto px-6 py-10" id="portfolioSlider">
    <div class="slider-viewport-card">
        <div class="slides-track" id="profileSlides">
            <!-- Slide 1: Profile -->
            <div class="portfolio-slide" data-slide="0">
                <div class="profile-info">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Profile Photo" class="profile-img">
                    <h2 class="title">Sudanshu Kumar</h2>
                    <p class="role">Software Developer</p>
                    <div class="social-icons">
                        <a href="#" class="icon" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" class="icon" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="icon" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                    <p class="description">
                        👋 Hello! I’m Sudanshu Kumar, an enthusiastic web developer with strong knowledge of HTML, CSS, JavaScript, React, Node, MongoDB and ExpressJS. Passionate about crafting user-friendly, accessible interfaces. Quick learner eager to explore modern web technologies & frameworks.
                    </p>
                    <div class="buttons">
                        <button id="downloadBtn" class="action-btn">Download Resume</button>
                        <button id="contactBtn" class="action-btn secondary">Contact Me</button>
                    </div>
                </div>
                <div class="education">
                    <h3>Education</h3>
                    <ul class="timeline" id="educationTimeline">
                        <li class="timeline-item active" data-year="2021-2025">
                            <span class="circle"></span>
                            <div class="content">
                                <span class="date">2021-2025</span>
                                <span class="degree">Bachelor of Technology in Computer Science and Engineering</span>
                                <span class="school">IP University</span>
                            </div>
                        </li>
                        <li class="timeline-item" data-year="2019-2021">
                            <span class="circle"></span>
                            <div class="content">
                                <span class="date">2019-2021</span>
                                <span class="degree">Senior Secondary</span>
                                <span class="school">Delhi Public School, Delhi (CBSE)</span>
                            </div>
                        </li>
                        <li class="timeline-item" data-year="2019">
                            <span class="circle"></span>
                            <div class="content">
                                <span class="date">2019</span>
                                <span class="degree">Secondary School</span>
                                <span class="school">Delhi Public School, Delhi (CBSE)</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Slide 2: Focus / Skills (example) -->
            <div class="portfolio-slide" data-slide="1">
                <div class="skills-panel">
                    <h3>Core Skills</h3>
                    <ul class="skills-list">
                        <li>HTML5 / Semantic Markup</li>
                        <li>CSS3 / Tailwind / Responsive Design</li>
                        <li>JavaScript / ES6+ / DOM APIs</li>
                        <li>React Fundamentals</li>
                        <li>Node.js + Express Basics</li>
                        <li>MongoDB CRUD & Aggregations</li>
                        <li>Version Control (Git)</li>
                    </ul>
                    <h4>Highlights</h4>
                    <p class="description">Built multiple small full‑stack projects, implemented authentication flows, optimized bundle size, and practiced clean component abstractions. Actively learning TypeScript & performance profiling.</p>
                </div>
                <div class="mini-gallery">
                    <h3>Recent Tools</h3>
                    <div class="tool-tags">
                        <span class="tag">VS Code</span>
                        <span class="tag">Figma</span>
                        <span class="tag">GitHub</span>
                        <span class="tag">Postman</span>
                        <span class="tag">Vite</span>
                        <span class="tag">Docker</span>
                    </div>
                    <div class="note-box">Always exploring better DX: linting, formatting, test automation & deployment pipelines.</div>
                </div>
            </div>
        </div>
        <button id="sliderPrev" class="slider-arrow prev" aria-label="Previous">❮</button>
        <button id="sliderNext" class="slider-arrow next" aria-label="Next">❯</button>
        <div class="slider-progress" aria-label="Progress"><div id="sliderProgressBar" class="bar"></div></div>
    </div>
</section>

@include('include.footer')

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/profile-slider.js') }}"></script>