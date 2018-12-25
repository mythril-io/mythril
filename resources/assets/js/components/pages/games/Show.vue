<template lang="html">
<div>

<section class="hero is-primary is-medium">
    <div class="hero-game-bg" id="animationBG" :style="bannerStyle(game.banner)">
        <div class="hero-body">
            <div class="container cover-title is-clearfix">
                <span class="is-pulled-left">
                    <h1 class="title" id="animationTitle">{{ this.game.title }}</h1>
                    <h2 class="subtitle" id="animationSub">Developed by: <em><a :href="'/games?developer=' + this.game.developer.id" class="dotted-border">{{ this.game.developer.name }}</a></em></h2>
                </span>
                <span class="is-pulled-right" id="animationAdd" style="margin-top: 20px">
                    <span class="button" :class="[userHasLibrary ? 'is-warning' : 'is-primary']"  @click="addLibrary">
                        <span>{{ userHasLibrary ? 'Edit Library' : 'Add to Library' }}</span>
                        <span class="icon is-small" v-if="userHasLibrary">
                          <i class="fas fa-edit"></i>
                        </span>
                    </span>
                    <a class="button" @click="addFavourite">
                        <span class="icon is-small" :class="{ heart: userHasFavourite }">
                            <i class="fas fa-heart"></i>
                        </span>
                    </a>
                    <a class="button" @click="addWishlist">
                        <span class="icon is-small" :class="{ wishlist: userHasWishlist }">
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                    </a>
                </span>
            </div>
        </div>
    </div>
</section>

<section class="tab-container">
	<div class="container cover-nav">

		<div class="tabs is-toggle">
		  <ul>
			<router-link tag="li" :to="{name: 'Game'}" exact>
			  <a>Summary</a>
			</router-link>
			<router-link tag="li" :to="{name: 'GameReviews'}">
			  <a>Reviews ({{ this.game.reviews_count }})</a>
			</router-link>
			<router-link tag="li" :to="{name: 'GameRecommendations'}">
			  <a>Recommendations ({{ this.game.recommendations_count }})</a>
			</router-link>
			<router-link tag="li" :to="{name: 'GameReleases'}">
			  <a>Releases ({{ this.game.releases.length }}) <span class="tag is-warning" style="margin-left: 7px;">Updated!</span></a>
			</router-link>
			<router-link tag="li" :to="{name: 'GameStats'}">
			  <a>Stats</a>
			</router-link>
		  </ul>
		</div>

	</div>
</section>

<section class="section">
    <div class="container is-clearfix">

        <div class="cover-sidebar is-pulled-left is-hidden-touch">
            <div class="card">
                <div class="card-image imageFade">
                    <figure class="image">
                        <img v-lazy="this.$store.state.cdnURL + 'games/icons/' + game.icon" class="imageFade" :alt="game.title">
                    </figure>
                </div>
            </div>
            <div class="break"></div>
            <div class="content">
				<div class="tags">
				  <span class="tag is-dark genre-link" v-for="genre in orderedGenres(game.genres)" :key="genre.name">
				  	<a :href="'/games?genres=[' + genre.id + ']'" :title="genre.name">{{ genre.acronym }}</a>
				  </span>
				</div>

                <h4>Platforms</h4>
                <article class="message">
                    <div class="message-body">
						<span v-for="platform in orderedPlatforms(this.game.releases)" :key="platform.name" class="comma">
							<a :href="'/games?platforms=[' + platform.id + ']'" :title="platform.name">{{ platform.acronym }}</a>
				        </span>
                    </div>
                </article>

                <nav-library></nav-library>

                <h4 v-if="this.game.libraries.length > 0">Recent User Activity</h4>

                    <div class="columns is-multiline is-mobile" v-if="this.game.libraries.length > 0">
                        <div class="column is-4" v-for="library in this.game.libraries">
                            <router-link :to="{name: 'User', params: { id: library.user.id }}">
                                <div class="card image is-square username-hover profile-icon" v-if="library.user.avatar">
                                    <img v-lazy="$store.state.cdnURL + 'users/avatars/' + library.user.avatar" style="object-fit: cover;" 
                                        :title="library.user.username + ': ' + library.play_status.name + ' (' + library.release.platform.acronym + ')'">
                                    <div class="username-text">{{ library.user.username }}</div>
                                </div>

                                <div class="card image is-square username-hover profile-icon" v-else>
                                    <img v-lazy="$store.state.cdnURL + 'users/avatars/default.jpg'" style="object-fit: cover;"
                                        :title="library.user.username + ': ' + library.play_status.name + ' (' + library.release.platform.acronym + ')'">
                                    <div class="username-text">{{ library.user.username }}</div>
                                </div>
                            </router-link>
                        </div>
                    </div>

            </div>
        </div>
        <div class="cover-content">
			<router-view :game="game"></router-view>
        </div>

    </div>
</section>
			    
	<authentication-modal :modalState="authModalState" v-on:close="toggleModal('auth')"></authentication-modal>	 
    <library-modal 
        v-if="user"
        :modalState="libraryModalState"
        :game="game"
        :user="user"
        v-on:update="value => { userHasLibrary = value }"
        v-on:close="toggleModal('library')">
    </library-modal> 
    <release-modal 
        v-if="user"
        :item="'Favourite'"
        :modalState="favouriteModalState"
        :game="game"
        :user="user"
        v-on:update="value => { updateFavourite(value) }"
        v-on:close="toggleModal('favourite')">
    </release-modal>  
    <release-modal 
        v-if="user"
        :item="'Wishlist'"
        :modalState="wishlistModalState"
        :game="game"
        :user="user"
        v-on:update="value => { updateWishlist(value) }"
        v-on:close="toggleModal('wishlist')">
    </release-modal>  
 

</div>
</template>

<script>
import AuthenticationModal from "../../utilities/AuthenticationModal.vue";
import ReleaseModal from "./modals/Release.vue";
import LibraryModal from "./modals/Library.vue";
import NavLibrary from "./components/NavLibrary.vue";

export default {
  props: ["user"],
  components: {
    "authentication-modal": AuthenticationModal,
    "release-modal": ReleaseModal,
    "library-modal": LibraryModal,
    "nav-library": NavLibrary
  },
  metaInfo() {
    return {
      titleTemplate: "%s - " + this.game.title
      // meta: [
      //         { vmid: 'description', name: 'description', content: this.description }
      // ]
    };
  },
  data() {
    return {
      game: [],
      authenticationModal: false,
      libraryModal: false,
      userHasLibrary: false,

      favouriteModal: false,
      userHasFavourite: false,

      wishlistModal: false,
      userHasWishlist: false
    };
  },
  filters: {
    truncate: function(string, value) {
      return string.substring(0, value) + "...";
    }
  },
  computed: {
    authModalState() {
      return this.authenticationModal;
    },
    libraryModalState() {
      return this.libraryModal;
    },
    favouriteModalState() {
      return this.favouriteModal;
    },
    wishlistModalState() {
      return this.wishlistModal;
    }
  },
  watch: {
    user: function() {
      this.checkUserInfo();
    },
    "$route.params.id": function(id) {
      this.initialize();
    }
  },
  methods: {
    avatarStyle(avatar) {
      return {
        backgroundImage:
          "url(" + this.$store.state.cdnURL + "users/avatars/" + avatar + ")",
        backgroundSize: "cover",
        backgroundPosition: "center top",
        backgroundRepeat: "no-repeat",
        width: "100%",
        height: "100%"
      };
    },
    bannerStyle(banner) {
      return {
        backgroundImage:
          "linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5))," +
          "url(" +
          this.$store.state.cdnURL +
          "games/banners/" +
          banner +
          ")"
      };
    },
    orderedPlatforms(releases) {
      var platforms = _.map(releases, "platform");
      var sorted = _.orderBy(platforms, ["name"], ["asc"]);
      return _.uniqBy(sorted, "id");
    },
    orderedGenres(genres) {
      return _.orderBy(genres, ["name"], ["asc"]);
    },
    toggleModal(modal) {
      if (modal == "auth") {
        this.authenticationModal = !this.authenticationModal;
      }
      if (modal == "library") {
        this.libraryModal = !this.libraryModal;
      }
      if (modal == "favourite") {
        this.favouriteModal = !this.favouriteModal;
      }
      if (modal == "wishlist") {
        this.wishlistModal = !this.wishlistModal;
      }
    },
    addLibrary() {
      if (!this.user) {
        this.toggleModal("auth");
      } else {
        this.toggleModal("library");
      }
    },
    addFavourite() {
      if (!this.user) {
        this.toggleModal("auth");
      } else {
        this.toggleModal("favourite");
      }
    },
    addWishlist() {
      if (!this.user) {
        this.toggleModal("auth");
      } else {
        this.toggleModal("wishlist");
      }
    },
    updateFavourite(value) {
      //console.log(JSON.parse(JSON.stringify(favourite)))
      this.userHasFavourite = value;
      this.toggleModal("favourite");
    },
    updateWishlist(value) {
      this.userHasWishlist = value;
      this.toggleModal("wishlist");
    },
    checkUserInfo() {
      if (!this.user) {
        this.userHasLibrary = false;
        this.userHasFavourite = false;
        this.userHasWishlist = false;
      } else {
        //Check Library
        axios
          .get(
            "/api/libraries/games/" +
              this.$route.params.id +
              "/users/" +
              this.user.id
          )
          .then(response => {
            this.userHasLibrary = true;
          })
          .catch(error => (this.userHasLibrary = false));

        //Check Favourite
        axios
          .get(
            "/api/favourites/games/" +
              this.$route.params.id +
              "/users/" +
              this.user.id
          )
          .then(response => {
            this.userHasFavourite = true;
          })
          .catch(error => (this.userHasFavourite = false));

        //Check Wishlist
        axios
          .get(
            "/api/wishlist/games/" +
              this.$route.params.id +
              "/users/" +
              this.user.id
          )
          .then(response => {
            this.userHasWishlist = true;
          })
          .catch(error => (this.userHasWishlist = false));
      }
    },
    initialize() {
      axios
        .get("/api/games/" + this.$route.params.id)
        .then(response => {
          if (response.data === 404) {
            this.$router.replace({ name: "Games" });
          }
          this.game = response.data;
        })
        .catch(error => this.$router.replace({ name: "Games" }));
      if (this.user) {
        this.checkUserInfo();
      }
    }
  },
  created() {
    this.initialize();
  }
};
</script>