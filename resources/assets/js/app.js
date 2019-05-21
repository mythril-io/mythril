
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.events = new Vue();

window.flash = function(message, type) {
	window.events.$emit('flash', message, type);
};

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import VueRouter  from 'vue-router'
import router     from './router'
import Vuex       from 'Vuex'
import store      from './store'
import Meta       from 'vue-meta'
import VeeValidate from 'vee-validate';
import infiniteScroll from 'vue-infinite-scroll'
import Buefy from 'buefy'
import VueScrollTo from 'vue-scrollto'
import checkView from 'vue-check-view'
var SocialSharing = require('vue-social-sharing');

Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(Meta)
Vue.use(infiniteScroll)
Vue.use(Buefy, {
  // defaultIconPack: 'fas',
  defaultIconPack: 'mdi',
})
Vue.use(VueScrollTo)
Vue.use(checkView)
Vue.use(SocialSharing);

const config = {
  errorBagName: 'errors', // change if property conflicts
  fieldsBagName: 'fields', 
  delay: 0, 
  locale: 'en', 
  dictionary: null, 
  strict: true, 
  classes: false, 
  classNames: {
    touched: 'touched', // the control has been blurred
    untouched: 'untouched', // the control hasn't been blurred
    valid: 'valid', // model is valid
    invalid: 'invalid', // model is invalid
    pristine: 'pristine', // control has not been interacted with
    dirty: 'dirty' // control has been interacted with
  },
  events: 'input|blur',
  inject: true,
  validity: false,
  aria: true,
  i18n: null, // the vue-i18n plugin instance,
  i18nRootKey: 'validations' // the nested key under which the validation messsages will be located
};
Vue.use(VeeValidate, config);

// import VueFuse from 'vue-fuse'
// Vue.use(VueFuse)

import VueLazyload from 'vue-lazyload'
Vue.use(VueLazyload, {
  preLoad: 1.3,
  // error: 'dist/error.png',
  // loading: 'https://image.ibb.co/dauszT/default.jpg',
  attempt: 1
})

/**
 * Custom Components
 */
import Paginate from 'vuejs-paginate'
Vue.component('paginate', Paginate)

Vue.component('app', require('./components/App.vue'));
Vue.component('flash', require('./components/utilities/Flash.vue'));
Vue.component('bulma-paginate', require('./components/utilities/BulmaPaginate.vue'));

/**
 * Uncomment below when compiling to production
 */
Vue.config.devtools = false
Vue.config.debug = false
Vue.config.silent = true


//Access Token
window.axios.defaults.headers.common['Authorization'] = 'Bearer' + localStorage.getItem('access_token');

//Global Filters

  //required imports
  import moment from 'moment';
  import numeral from 'numeral';

  //Date Filter
  Vue.filter('dateFormat', function (date, datetype) {
    if(date)
    {
      if(!datetype) { 
        datetype = 1; 
      }
      switch(datetype) {
        case 1:
          return moment(date).format("MMM Do YYYY");
          break;
        case 2:
          return moment(date).format("MMM YYYY");
          break;
        case 3:
          return moment(date).format("YYYY");
          break;
        default:
          return moment(date).format("MMM Do YYYY");
      }
    }
    else
    {
      return "TBD";
    }
  })

  //Number Formating
  Vue.filter('numberFormat', function (number) {
    return numeral(number).format('0,0');
  })

  //Number Formating
  Vue.filter('numberFormatK', function (number) {
    return numeral(number).format('0a');
  })


  //Percentage Formating
  Vue.filter('percentageFormat', function (number) {
    return numeral(number).format('0%');
  })

  //Date Formating (ex. 3 days ago)
  Vue.filter('ago', function (date, user) {
    if(user) {
      if(user.timezone) {
          return moment.utc(date).tz(user.timezone).fromNow();
      }               
    }
    return moment.utc(date).local().fromNow();
  })

  Vue.filter('truncate', function (string, value) {
    if(string.length < value) { return string }
    return string.substring(0, value) + '...';
  })

const app = new Vue({
    el: '#app',
    router,
    store,
    metaInfo: {
      title: 'Mythril',
    }
});


