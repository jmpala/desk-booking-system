import Vue from 'vue';
import App from '@/components/office-map';
import 'leaflet/dist/leaflet.css';

new Vue({
  el: '#app',
  template: '<App/>',
  components: { App },
});
