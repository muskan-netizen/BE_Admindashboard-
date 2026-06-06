import Vue from 'vue';
import VueRouter from 'vue-router'

Vue.use(VueRouter)

 import Test from './test/Test';
import App from './test/App';
import About from './test/Home';
import Program from './test/program';
import Bind from './test/binding';
import Event from './test/events'; 
import Trans from './test/transition'; 
import Mixin from './test/mixin';
import Check2 from './test/check2';
//import Hello from './pages/shop/shoes/components/about.vue';
//import Home from './pages/page/home/home';
/*{
            path: '/home',
            name: 'home',
            component: Home
        },
        {
            path: '/hello',
            name: 'hello',
            component: Hello
        },*/
const router = new VueRouter({
    mode: 'history',
    routes: [
        
        {
            path: '/about',
            name: 'about',
            component: About
        },
        {
            path: '/test',
            name: 'test',
            component: Test,
        },
        {
            path: '/program',
            name: 'program',
            component: Program,
        },
        {
            path: '/bind',
            name: 'bind',
            component: Bind,
        },
        {
            path: '/event',
            name: 'event',
            component: Event,
        },
        {
            path: '/trans',
            name: 'trans',
            component: Trans,
        },
        {
            path: '/mixin',
            name: 'mixin',
            component: Mixin,
        },
        {
            path: '/check2',
            name: 'check2',
            component: Check2,
        },
        
    ],
});

//var _obj = { fname: "Raj", lname: "harami"}

const app = new Vue({
  el: '#app_main_div',
  components: {
    App
  },
  render: h => h(App),
  router,
});

Vue.component('button-counter', {
  data: function () {
    return {
      count: 0
    }
  },
  template: '<button v-on:click="count++">You clicked me {{ count }} times.</button>'
});
