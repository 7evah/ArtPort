import { createApp } from 'vue';
import App from './components/App.vue';
import router from './routes';
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';


const app = createApp(App);

app.use(router);
app.mount('#app');
