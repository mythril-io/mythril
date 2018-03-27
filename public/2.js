webpackJsonp([2],{

/***/ 65:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(1)
/* script */
var __vue_script__ = __webpack_require__(71)
/* template */
var __vue_template__ = __webpack_require__(72)
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
Component.options.__file = "resources/assets/js/components/pages/Register.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-6214f8f6", Component.options)
  } else {
    hotAPI.reload("data-v-6214f8f6", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 71:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__utilities_Auth__ = __webpack_require__(5);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1_vee_validate__ = __webpack_require__(17);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_nprogress__ = __webpack_require__(18);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2_nprogress___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2_nprogress__);
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
      email: 'vincent@email.com',
      username: 'Vincent',
      password: 'password'
    };
  },

  methods: {
    validateBeforeSubmit: function validateBeforeSubmit() {
      var _this = this;

      this.$validator.validateAll().then(function (result) {
        if (result) {
          _this.register();
        }
      });
    },
    register: function register() {
      var _this2 = this;

      __WEBPACK_IMPORTED_MODULE_2_nprogress___default.a.start();
      var validation = true;
      axios.post('/api/auth/register', { email: this.email, username: this.username, password: this.password }).catch(function (error) {
        if (error.response.data.errors) {
          __WEBPACK_IMPORTED_MODULE_2_nprogress___default.a.done();
          validation = false;
          var errorsReadable = _this2.errorHandler(error.response.data.errors);
          flash(errorsReadable, 'error');
        }
      }).then(function (response) {
        if (validation) {
          //Auth.storeTokens(response);
          //Auth.setAuthHeader();

          //Update VueX State with User Object
          // axios.post('/api/auth/me').then((response) => { 
          //     this.$store.commit('userLoggedIn', response.data);
          // }).catch((error) => console.log(error));

          //Notify user of success
          flash('Sucessfully registered :) Please confirm your email.', 'success');

          //Redirect to Home Page
          _this2.$router.replace({ name: 'Home' });
        }
        validation = true;
        __WEBPACK_IMPORTED_MODULE_2_nprogress___default.a.done();
      });
    },
    errorHandler: function errorHandler(errorsArray) {
      var i,
          errorsReadable = "";
      if (errorsArray.email) {
        for (i = 0; i < errorsArray.email.length; i++) {
          errorsReadable += errorsArray.email[i] + "<br>";
        }
      }
      if (errorsArray.username) {
        for (i = 0; i < errorsArray.username.length; i++) {
          errorsReadable += errorsArray.username[i] + "<br>";
        }
      }
      if (errorsArray.password) {
        for (i = 0; i < errorsArray.password.length; i++) {
          errorsReadable += errorsArray.password[i] + "<br>";
        }
      }
      return errorsReadable;
    }
  }
});

/***/ }),

/***/ 72:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("form", [
    _c("div", { staticClass: "field" }, [
      _c("p", { staticClass: "control has-icons-left" }, [
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
              value: _vm.username,
              expression: "username"
            },
            {
              name: "validate",
              rawName: "v-validate",
              value: "required",
              expression: "'required'"
            }
          ],
          class: { input: true, "is-danger": _vm.errors.has("username") },
          attrs: { type: "text", placeholder: "Username", name: "username" },
          domProps: { value: _vm.username },
          on: {
            input: function($event) {
              if ($event.target.composing) {
                return
              }
              _vm.username = $event.target.value
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
                value: _vm.errors.has("username"),
                expression: "errors.has('username')"
              }
            ],
            staticClass: "help is-danger"
          },
          [_vm._v(_vm._s(_vm.errors.first("username")))]
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
        _vm._m(2),
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
          [_vm._v("\n        Register\n      ")]
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
      _c("i", { staticClass: "fa fa-user" })
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
    require("vue-hot-reload-api")      .rerender("data-v-6214f8f6", module.exports)
  }
}

/***/ })

});