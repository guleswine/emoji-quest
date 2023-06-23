import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


import {createApp} from 'vue'


import Game from './components/Game.vue'
import FlashMessage from '@smartweb/vue-flash-message';

const app = createApp(Game);
app.use(FlashMessage);
app.mount('#app');
