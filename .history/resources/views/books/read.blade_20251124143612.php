@extends('include.')

@section('content')
<link rel="stylesheet" href="{{ asset('css/book-reader.css') }}">
<div class="container">
    <div class="profile-card">
        <div class="profile-info">
            <img src="{{ asset('images/profile.jpg') }}" alt="Profile Photo" class="profile-img">
            <h2>Sudanshu Kumar</h2>
            <p class="role">Software Developer</p>
            <div class="social-icons">
                <a href="#" class="icon"><i class="fab fa-github"></i></a>
                <a href="#" class="icon"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="icon"><i class="fab fa-twitter"></i></a>
            </div>
            <p class="description">
                👋 Hello! I’m Sudanshu Kumar, a enthusiastic web developer with strong knowledge of HTML, CSS, JavaScript, React, Node, MongoDB and ExpressJS. Passionate about creating user-friendly and responsive websites. Quick learner, eager to explore new web technologies and frameworks. Looking for opportunities to grow and contribute as a web developer.
            </p>
            <div class="buttons">
                <button id="download-btn">Download Resume</button>
                <button id="contact-btn">Contact Me</button>
            </div>
        </div>
        <div class="education">
            <h3>Education</h3>
            <ul class="timeline">
                <li>
                    <span class="circle"></span>
                    <span class="date">2021-2025</span>
                    <span class="degree">Bachelor of Technology in Computer Science and Engineering</span>
                    <span class="school">IP University</span>
                </li>
                <li>
                    <span class="circle"></span>
                    <span class="date">2019-2021</span>
                    <span class="degree">Senior Secondary<br><span class="school">Delhi Public School, Delhi<br>Board: CBSE</span></span>
                </li>
                <li>
                    <span class="circle"></span>
                    <span class="date">2019</span>
                    <span class="degree">Secondary School<br><span class="school">Delhi Public School, Delhi<br>Board: CBSE</span></span>
                </li>
            </ul>
        </div>
    </div>
</div>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
<script src="{{ asset('js/dynamic-book-pager.js') }}"></script>
@endsection