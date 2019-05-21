<template>
	<div>
		<nav class="navbar is-fixed-top has-shadow" :class="{ 'is-spaced': !scrolled }" style="transition: 0.3s;">
			<div class="container">
			  <div class="navbar-brand">
			    <router-link class="navbar-item" :to="{name: 'Home'}">
	    			<i class="material-icons logo">videogame_asset</i>
	    			<img :src="this.$store.state.cdnURL + 'logo/1.png'" alt="Mythril Logo">
			    </router-link>
			    <div class="navbar-burger burger" data-target="navMenuIndex" 
			    	:class="{ 'is-active': navBurger }" 
			    	@click="navBurger=!navBurger">
			      <span></span>
			      <span></span>
			      <span></span>
			    </div>
			  </div>

			  <div id="navMenuIndex" class="navbar-menu" :class="{ 'is-active': navBurger }" @click="navBurger=false">
			    <div class="navbar-start">
			      <div class="navbar-item has-dropdown is-hoverable">
			        <router-link class="navbar-link" :to="{name: 'Games'}" exact>
			          Games
			        </router-link>
			        <div class="navbar-dropdown">
			          <router-link class="navbar-item" :to="{name: 'Games'}" exact>
			            Browse Games
			          </router-link>
<!-- 			          <a class="navbar-item" href="#">
			            Game Franchises
			          </a> -->
			          <hr class="navbar-divider">
			          <router-link class="navbar-item" :to="{name: 'Reviews'}">
			            Reviews
			          </router-link>
			          <router-link class="navbar-item" :to="{name: 'Recommendations'}">
			            Recommendations
			          </router-link>
			        </div>
			      </div>

			      <div class="navbar-item has-dropdown is-hoverable">
			        <a class="navbar-link" href="#">
			          Community <span class="tag is-success" style="margin-left: 7px;">New!</span>
			        </a>
			        <div class="navbar-dropdown">
			          <router-link class="navbar-item" :to="{name: 'Forums' }" exact>
			            <!-- <span class="icon has-text-info" style="margin-top: 2px;">
										<i class="fas fa-info-circle"></i>
									</span> -->
 									<div>Forums</div><span class="tag is-success" style="margin-left: 7px;">New!</span>
			          </router-link>
			          <router-link class="navbar-item" :to="{name: 'Users'}" exact>
			            Users
			          </router-link>
			          <hr class="navbar-divider">
			          <router-link class="navbar-item" :to="{name: 'Staff'}">
			            Staff
			          </router-link>
			        </div>
			      </div>
			    </div>

			    <div class="navbar-end">

					<div class="navbar-item">
						<div class="field is-grouped">

							<p class="control has-icons-left is-hidden-touch">
								<b-field>

									<b-autocomplete
											:data="games"
											placeholder="Search..."
											field="title"
											:loading="isFetching"
											icon="magnify"
											@typing="getData"
											>
                        <template slot-scope="props">
                            <div class="media">
                                <div class="media-left">
                                        <img width="32" :src="`${cdnURL}games/icons/${props.option.icon}`">
                                </div>
                                <div class="media-content">
                                    <strong>{{ props.option.title }}</strong>
                                    <br>
                                    <small>Popularity: #{{ props.option.popularity_rank }} |
                                        Score: {{ props.option.score ? (props.option.score | percentageFormat) + '%' : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </template>
									</b-autocomplete>
								</b-field>

							  <!-- <input class="input nav-search" type="text" placeholder="Search..." style="width: 200px"
							  	v-model="search" 
							  	v-on:keyup.enter="submit">
							  <span class="icon is-left">
							    <i class="fas fa-search"></i>
							  </span> -->
							</p>
							
<!-- 							<p class="control is-hidden-mobile" v-if="user">
								<button class="button is-primary badge is-badge-outlined" data-badge="2">
								<span class="icon">
							    	<i class="fas fa-envelope"></i>
							  	</span>
								</button>
							</p>

							<p class="control is-hidden-mobile" v-if="user">
								<button class="button is-primary badge is-badge-outlined" data-badge="7">
								<span class="icon">
							    	<i class="fas fa-bell"></i>
							  	</span>
								</button>
							</p> -->

							<p class="control" v-if="!user">
							  <router-link class="button" :to="{name: 'Login'}">
							    <span class="icon is-small">
							      <i class="fas fa-sign-in-alt"></i>
							    </span>
							    <span>Login</span>
							  </router-link>
							</p>

						    <p class="control" v-if="!user">
						      <router-link class="button is-primary" :to="{name: 'Register'}">
						        <span class="icon">
						          <i class="fas fa-user-plus"></i>
						        </span>
						        <span>Register</span>
						      </router-link>
						    </p>

						</div>
					</div>

					<div class="navbar-item has-dropdown is-hoverable" v-if="user">

					  <a class="navbar-link">
					  	<figure class="image is-32x32 is-hidden-touch" v-bind:style="avatarStyle">
						</figure>
					    {{ user.username }} 
					  </a>

					  <div class="navbar-dropdown is-right">
					    <router-link class="navbar-item" :to="{name: 'User', params: { id: user.id }}" exact>
					      Profile
					    </router-link>
					    <router-link class="navbar-item" :to="{name: 'UserLibrary', params: { id: user.id }}">
					      Library
					    </router-link>
					    <router-link class="navbar-item" :to="{name: 'UserReviews', params: { id: user.id }}">
					      Reviews
					    </router-link>
					    <router-link class="navbar-item" :to="{name: 'UserRecommendations', params: { id: user.id }}">
					      Recommendations
					    </router-link>
					    <router-link class="navbar-item" :to="{name: 'Admin'}" v-if="user.id == 1">
					      Admin
					    </router-link>
					    <hr class="navbar-divider">
					    <router-link class="navbar-item" :to="{name: 'UserSettings'}">
					      <span class="icon is-small">
							<i class="fas fa-cog"></i>
						  </span>&nbsp;
					      Settings
					    </router-link>
					    <a class="navbar-item" href="#" @click.prevent="logout">
					      <span class="icon is-small">
							<i class="fas fa-sign-out-alt"></i>
						  </span>&nbsp;
					      Logout
					    </a>
					  </div>
					</div>
			    </div>
			  </div>
			</div>
		</nav>

    </div>
</template>

<script>
	export default {
		props: [ 'user' ],
		data() {
			return {
				navBurger: false,
				search: '',
				scrolled: false,

				games: [],
				isFetching: false,
				selected: null,
			}
		},
		computed: {
			userAvatarURL() {
				if(this.user.avatar) { return this.$store.state.cdnURL + 'users/avatars/' + this.user.avatar;}
				else { return this.$store.state.cdnURL + 'users/avatars/default.jpg' }
			},
			avatarStyle() {
				return {
							backgroundImage: 'url(' + this.userAvatarURL + ')',
							backgroundSize: 'cover',
							backgroundPosition: 'center top', 
							backgroundRepeat: 'no-repeat',
					'-webkit-box-shadow': '#e1e7f5 0 0 0 1px inset',
							boxShadow: '#e1e7f5 0 0 0 1px inset',
							marginRight: '10px'
				}
			}
		},
		methods: {
			logout() {
					this.$emit('logout')
			},
			submit() {
				this.$router.push({ name: 'Games', query: { search: this.search  }})
			},
			handleScroll () {
			this.scrolled = window.scrollY > 100;
			},
			getData() {
				alert('hello')
			},
   		getAsyncGames: _.debounce(function (name) {
                if (!name.length) {
                    this.games = []
                    return
                }
                this.isFetching = true
                axios.get(`/api/games?search=${name}`)
                .then(response => {
                    this.games = []
                    this.games = response.data.data;
										this.isFetching = false
										console.log(this.games)
                })
                .catch(error => console.log("Games array not updated."));
            }, 500)
		},
		created () {
		  window.addEventListener('scroll', this.handleScroll);
		},
		destroyed () {
		  window.removeEventListener('scroll', this.handleScroll);
		}
	}
</script>