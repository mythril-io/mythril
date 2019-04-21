import VueRouter from 'vue-router'
import Auth from './utilities/Auth'
import store from './store'
import NProgress from 'nprogress'

// Define route components
const Home = require('./components/pages/Index.vue')
//const Login = (resolve) => require(['./components/pages/Login.vue'], resolve)
const Login = require('./components/pages/Login.vue')
const Register = require('./components/pages/Register.vue')
const UserSettings = require('./components/pages/settings/Index.vue')
const Game = require('./components/pages/games/Show.vue')
const User = require('./components/pages/users/Show.vue')
const Admin = require('./components/pages/admin/Index.vue')
const VerifyEmail = require('./components/pages/VerifyEmail.vue')
const Staff = require('./components/pages/site/Staff.vue')
const Faq = require('./components/pages/site/Faq.vue')
const PrivacyPolicy = require('./components/pages/site/PrivacyPolicy.vue')
const TermsOfService = require('./components/pages/site/TermsOfService.vue')
const Forums = require('./components/pages/forums/Index.vue')

// Define routes in VueRouter instance
const router = new VueRouter({
    linkActiveClass: 'is-active',
    mode: 'history',
    base: __dirname,
      routes: [
        { 
        	path: '/', 
        	name: 'Home', 
        	component: Home, 
        	meta: { title: 'Home' }
        }, { 
        	path: '/login', 
        	name: 'Login', 
        	component: Login,  
        	meta: { title: 'Login', requiresGuest: true } 
        }, { 
        	path: '/register', 
        	name: 'Register', 
        	component: Register,  
        	meta: { title: 'Register', requiresGuest: true } 
        }, { 
        	path: '/verify/:code', 
        	name: 'VerifyEmail', 
        	component: VerifyEmail,
        	meta: { title: 'Verify Email', requiresGuest: true }
        }, { 
          path: '/staff', 
          name: 'Staff', 
          component: Staff,  
          meta: { title: 'Staff'} 
        }, { 
          path: '/faq', 
          name: 'Faq', 
          component: Faq,  
          meta: { title: 'Frequently Asked Questions'} 
        }, { 
          path: '/privacy-policy', 
          name: 'PrivacyPolicy', 
          component: PrivacyPolicy,  
          meta: { title: 'Privacy Policy'} 
        }, { 
          path: '/terms-of-service', 
          name: 'TermsOfService', 
          component: TermsOfService,  
          meta: { title: 'Terms of Service'} 
        }, { 
          path: '/games', 
          name: 'Games', 
          components: {
            reload: require('./components/pages/games/Index.vue')
          },   
          meta: { title: 'Games' }
        }, {
          path: '/games/:id(\\d+)',
          component: Game,
          children: [{
              path: '',
              name: 'Game',
              component: require('./components/pages/games/tabs/Summary.vue')
            }, {
              path: 'reviews',
              name: 'GameReviews',
              component: require('./components/pages/games/tabs/Reviews.vue')
            }, {
              path: 'recommendations',
              name: 'GameRecommendations',
              component: require('./components/pages/games/tabs/Recommendations.vue')
            }, {
              path: 'releases',
              name: 'GameReleases',
              component: require('./components/pages/games/tabs/Releases.vue')
            }, {
              path: 'stats',
              name: 'GameStats',
              component: require('./components/pages/games/tabs/Stats.vue')
            }]
        }, { 
          path: '/forums', redirect: { name: 'Forums', params: { feed: 'recent' } }
        }, { 
          path: '/forums/:tag?', 
          name: 'Forums', 
          components: {
            reload: Forums
          },
          meta: { title: 'Forums'},
        },  { 
          path: '/forums/create', 
          name: 'CreateThread', 
          component: require('./components/pages/forums/discussions/create.vue'),
          meta: { title: 'Create a Thread', requiresAuth: true }
        }, { 
          path: '/forums/discussions/:id/:slug', 
          name: 'Discussion', 
          component: require('./components/pages/forums/discussions/show.vue'),
          meta: { title: 'Discussion' }
        }, { 
          path: '/users', 
          name: 'Users', 
          components: {
            reload: require('./components/pages/users/Index.vue')
          },   
          meta: { title: 'Users' }
        }, {
          path: '/users/:id(\\d+)',
          component: User,
          children: [{
              path: '',
              name: 'User',
              component: require('./components/pages/users/tabs/Summary.vue')
            }, {
              path: 'library',
              name: 'UserLibrary',
              component: require('./components/pages/users/tabs/Library.vue'),
              children: [{ 
                  path: ':status', 
                  name: 'UserLibrarySection'
              }]
            }, {
              path: 'reviews',
              name: 'UserReviews',
              component: require('./components/pages/users/tabs/Reviews.vue')
            }, {
              path: 'recommendations',
              name: 'UserRecommendations',
              component: require('./components/pages/users/tabs/Recommendations.vue')
            }, {
              path: 'followers',
              name: 'UserFollowers',
              component: require('./components/pages/users/tabs/Followers.vue')
            }, {
              path: 'following',
              name: 'UserFollowing',
              component: require('./components/pages/users/tabs/Following.vue')
            }, {
              path: 'stats',
              name: 'UserStats',
              component: require('./components/pages/users/tabs/Stats.vue')
            }]
        }, {
          path: '/settings',
          component: UserSettings,
          meta: { title: 'User Settings', requiresAuth: true },
          children: [{
              path: '',
              name: 'UserSettings',
              component: require('./components/pages/settings/tabs/profile.vue')
            }, {
              path: 'images',
              name: 'UserSettingsImages',
              component: require('./components/pages/settings/tabs/images.vue')
            }, {
              path: 'social',
              name: 'UserSettingsSocial',
              component: require('./components/pages/settings/tabs/social.vue')
            }, {
              path: 'password',
              name: 'UserSettingsPassword',
              component: require('./components/pages/settings/tabs/password.vue')
            }]
        }, { 
          path: '/reviews', 
          name: 'Reviews', 
          components: {
            reload: require('./components/pages/reviews/index.vue')
          },
          meta: { title: 'Reviews' }
        }, { 
          path: '/reviews/create', 
          name: 'CreateReview', 
          component: require('./components/pages/reviews/create.vue'),
          meta: { title: 'Create a Review', requiresAuth: true }
        }, { 
          path: '/reviews/:id(\\d+)', 
          name: 'Review', 
          component: require('./components/pages/reviews/show.vue'),
          meta: { title: 'Review' }
        }, { 
          path: '/recommendations', 
          name: 'Recommendations', 
          components: {
            reload: require('./components/pages/recommendations/index.vue')
          },
          meta: { title: 'Recommendations' }
        }, { 
          path: '/recommendations/create', 
          name: 'CreateRecommendation', 
          component: require('./components/pages/recommendations/create.vue'),
          meta: { title: 'Create a Recommendation', requiresAuth: true }
        }, { 
          path: '/recommendations/:id(\\d+)', 
          name: 'Recommendation', 
          component: require('./components/pages/recommendations/show.vue'),
          meta: { title: 'Recommendation' }
        }, { 
        	path: '*', 
        	component: Home 
        }, {
          path: '/admin',
          component: Admin,
          meta: {auth: {roles: 'admin'}, requiresAuth: true },
          beforeEnter: checkAdmin,
          children: [{
              path: '',
              name: 'Admin',
              component: require('./components/pages/admin/Dashboard.vue')
            }, {
              path: 'games',
              name: 'AdminGame',
              component: require('./components/pages/admin/games/Game.vue')
            }, {
              path: 'games-edit',
              name: 'AdminGameEdit',
              component: require('./components/pages/admin/games/GameEditDelete.vue')
            }, {
              path: 'releases',
              name: 'AdminReleaseAdd',
              component: require('./components/pages/admin/games/ReleaseAdd.vue')
            }, {
              path: 'releases-edit',
              name: 'AdminReleaseEdit',
              component: require('./components/pages/admin/games/ReleaseEditDelete.vue')
            }, {
              path: 'genres',
              name: 'AdminGenre',
              component: require('./components/pages/admin/genres/Index.vue')
            }, {
              path: 'publishers',
              name: 'AdminPublisher',
              component: require('./components/pages/admin/publishers/Index.vue')
            }, {
              path: 'developers',
              name: 'AdminDeveloper',
              component: require('./components/pages/admin/developers/Index.vue')
            }, {
              path: 'platforms',
              name: 'AdminPlatform',
              component: require('./components/pages/admin/platforms/Index.vue')
          }]
        }
      ],
      scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
          return savedPosition
        } else {
          return { x: 0, y: 0 }
        }
      }
})

// Register Global Navigation Guard
router.beforeEach((to, from, next) => {
	NProgress.start();

	//Change Document Title
	//document.title = "Mythril - " + to.meta.title

	//Check User Authentication
	if(!Auth.isAuthenticated())
	{
		Auth.removeSession();
	}
	else 
	{ 
		Auth.setAuthHeader();
		Auth.checkTokenRefresh();
	}

	// If user is not logged in, redirect to login page.
	if (to.matched.some(record => record.meta.requiresAuth)) {
		if (!Auth.isAuthenticated()) 
		{
			next({
			path: '/login',
			query: { redirect: to.fullPath }
			})
		} 
		else { next() }
	} 
	// If user is logged in, prevent access to login/register.
	else if (to.matched.some(record => record.meta.requiresGuest)) {
		if (Auth.isAuthenticated()) 
		{
			router.push({ name: 'Home'})
		} 
		else { next() }
	}
	else { next() }
})

router.afterEach((to, from) => {
  NProgress.done();
})

function checkAdmin(to, from, next) {
		axios.post('/api/auth/checkAdmin').then((response) => { 
	    if(response.data)
			{
		  		next()
			}
			else { router.push({ name: 'Home'}) }
		}).catch((error) => console.log(error))
}

export default router;