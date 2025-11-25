document.getElementById('download-btn').addEventListener('click', function() {
  window.open('/files/Resume.pdf', '_blank');
});

document.getElementById('contact-btn').addEventListener('click', function() {
  window.location.href = 'mailto:sudanshukumar@example.com';
});