//se requiere el paquete de bootstrap
require('./bootstrap');
//se requiere el paquete de vue
window.Vue = require('vue');
//se importa el paquete de vue star rating
import StarRating from 'vue-star-rating';
//se importa  el componente de tipo
Vue.component('type-component', require('./components/TypeComponent.vue').default);
//se importa el componente del formulario de producto
Vue.component('producto-form', require('./components/ProductoFormComponent.vue').default);
//se importa el componente de producto
Vue.component('producto-component', require('./components/ProductoComponent.vue').default);
//se importa el componente del formulario del formulario de tipo
Vue.component('type-form', require('./components/TypeFormComponent.vue').default);
//se importa el componente de usuario
Vue.component('user-component', require('./components/UserComponent.vue').default);
//se importa el componente de catalogo
Vue.component('catalogo-component', require('./components/CatalogoComponent.vue').default);
//se importa el componente que obtiene los ultimos  productos
Vue.component('last-component', require('./components/LastProducts.vue').default);
//se importa el componente de perfil de usuario
Vue.component('profile-component', require('./components/ProfileComponent.vue').default);
//se importa el componente de producto por tipo
Vue.component('producttype-component', require('./components/ProductTypeComponent.vue').default);
//se importa el componente de vue star rating
Vue.component('star-rating', StarRating);
//se importa el paquete de sweet alert
import swal from 'sweetalert2'
window.swal = swal;
//configuracion de sweet alert
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;
//evento de vue
window.EventBus = new Vue();
//segundo evento de vue
window.EventBus2 = new Vue();
//renderizador de vue
const app = new Vue({
    el: '#app',
});
//configuracion de login y registro
//contante que almacena el boton
const registerButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');
//evento click del boton de registro
registerButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});
//evento click del boton de login
signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});