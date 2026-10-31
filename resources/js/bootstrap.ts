import axios from 'axios';
import { bootstrapWayfinderDefaults } from '@/Composables/bootstrapWayfinderDefaults';

bootstrapWayfinderDefaults();

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
