import './bootstrap';
module.exports = {
  darkMode: 'class', // class байх ёстой!
  // ... бусад тохиргоо
}
import './hero-animation.js';
import './authors-animations'; // authors grid animations
import { initAuthorsAnimations } from './authors-animations.js';

document.addEventListener('DOMContentLoaded', () => {
  initAuthorsAnimations();
});

// ... бусад код