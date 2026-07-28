import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import Swiper from 'swiper';
import 'swiper/css';

window.Alpine = Alpine;
window.Swiper = Swiper;

Alpine.plugin(collapse);
Alpine.start();
