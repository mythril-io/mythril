webpackJsonp([3],{

/***/ 64:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(69)
/* template */
var __vue_template__ = __webpack_require__(70)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/assets/js/components/pages/Login.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-79f6bdf7", Component.options)
  } else {
    hotAPI.reload("data-v-79f6bdf7", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 69:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utilities_Auth__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vee_validate__ = __webpack_require__(17);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      email: 'cloud@email.com',
      password: 'password'
    };
  },

  methods: {
    validateBeforeSubmit: function validateBeforeSubmit() {
      var _this = this;

      this.$validator.validateAll().then(function (result) {
        if (result) {
          _this.login();
        }
      });
    },
    login: function login() {
      var _this2 = this;

      axios.post('/api/auth/login', { email: this.email, password: this.password }).then(function (response) {
        __WEBPACK_IMPORTED_MODULE_0__utilities_Auth__["a" /* default */].storeTokens(response);
        __WEBPACK_IMPORTED_MODULE_0__utilities_Auth__["a" /* default */].setAuthHeader();

        //Update VueX State with User Object
        axios.post('/api/auth/me').then(function (response) {
          _this2.$store.commit('userLoggedIn', response.data);
        }).catch(function (error) {
          return console.log(error);
        });

        //Notify user of success
        flash('Welcome back!', 'success');

        //Redirect to Home Page
        _this2.$router.replace({ name: 'Home' });
      }).catch(function (error) {
        return flash(error.response.data.error, 'error');
      });
    }
  }
});

/***/ }),

/***/ 70:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("form", [
    _c("div", { staticClass: "field" }, [
      _c("p", { staticClass: "control has-icons-left has-icons-right" }, [
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.email,
              expression: "email"
            },
            {
              name: "validate",
              rawName: "v-validate",
              value: "required|email",
              expression: "'required|email'"
            }
          ],
          class: { input: true, "is-danger": _vm.errors.has("email") },
          attrs: { type: "email", placeholder: "Email", name: "email" },
          domProps: { value: _vm.email },
          on: {
            input: function($event) {
              if ($event.target.composing) {
                return
              }
              _vm.email = $event.target.value
            }
          }
        }),
        _vm._v(" "),
        _vm._m(0),
        _vm._v(" "),
        _c(
          "span",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.errors.has("email"),
                expression: "errors.has('email')"
              }
            ],
            staticClass: "help is-danger"
          },
          [_vm._v(_vm._s(_vm.errors.first("email")))]
        )
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "field" }, [
      _c("p", { staticClass: "control has-icons-left" }, [
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.password,
              expression: "password"
            },
            {
              name: "validate",
              rawName: "v-validate",
              value: "required",
              expression: "'required'"
            }
          ],
          staticClass: "input",
          attrs: {
            type: "password",
            placeholder: "Password",
            name: "password"
          },
          domProps: { value: _vm.password },
          on: {
            input: function($event) {
              if ($event.target.composing) {
                return
              }
              _vm.password = $event.target.value
            }
          }
        }),
        _vm._v(" "),
        _vm._m(1),
        _vm._v(" "),
        _c(
          "span",
          {
            directives: [
              {
                name: "show",
                rawName: "v-show",
                value: _vm.errors.has("password"),
                expression: "errors.has('password')"
              }
            ],
            staticClass: "help is-danger"
          },
          [_vm._v(_vm._s(_vm.errors.first("password")))]
        )
      ])
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "field" }, [
      _c("p", { staticClass: "control" }, [
        _c(
          "button",
          {
            staticClass: "button is-primary",
            attrs: { disabled: _vm.errors.any() },
            on: {
              click: function($event) {
                $event.preventDefault()
                _vm.validateBeforeSubmit($event)
              }
            }
          },
          [_vm._v("\n        Login\n      ")]
        )
      ])
    ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("span", { staticClass: "icon is-small is-left" }, [
      _c("i", { staticClass: "fa fa-envelope" })
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("span", { staticClass: "icon is-small is-left" }, [
      _c("i", { staticClass: "fa fa-lock" })
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-79f6bdf7", module.exports)
  }
}

/***/ })

});