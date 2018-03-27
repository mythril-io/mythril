<template>
	<div>
		<section class="hero is-primary is-medium">
			<div class="hero-bg" id="animationBG" :style="bannerStyle">
				<div class="hero-head">
				</div>
				<div class="hero-body">
				<div class="container">
					<h1 class="title" id="animationTitle">
					Welcome to Mythril!
					</h1>
					<h2 class="subtitle" id="animationSub">
					Track, discover, review and recommend video games.
					</h2>
				</div>
				</div>
			</div>
		</section>

		<!-- Trending Games, Reviews, Recommendations -->

		<section class="section">
			<div class="container">
				<trending-games :trending="trending"></trending-games>
				
				<div class="section-break"></div>
				<div class="columns is-multiline">
					<div class="column is-12-tablet is-6-widescreen">
						<reviews></reviews>
					</div>
					<div class="column is-12-tablet is-6-widescreen">
						<recommendations></recommendations>
					</div>
				</div>
			</div>
		</section>


		<!-- Stats -->

		<section class="hero is-primary">
		  <div class="hero-body">
		    <div class="container">

				<site-stats
					:gamesCount="games_count"
					:usersCount="users_count"
					:reviewsCount="reviews_count"
					:recommendationsCount="recommendations_count"
				></site-stats>

		    </div>
		  </div>
		</section>

		<!-- Recent User Activity -->

		<section class="section">
			<div class="container">
			
				<recent-user-activity :recentUserActivity="recent_user_activity"></recent-user-activity>

			</div>
		</section>

		<!-- Footer Links -->

		<section class="hero is-primary">
		  <div class="hero-body">
		    <div class="container">

				<top-games :ranked="ranked" :popular="popular"></top-games>

		  	</div>
		  </div>
		</section>

  </div>
</template>

<script>
import TrendingGames from './home/TrendingGames.vue';
import Reviews from './home/Reviews.vue';
import Recommendations from './home/Recommendations.vue';
import SiteStats from './home/SiteStats.vue';
import RecentUserActivity from './home/RecentUserActivity.vue';
import TopGames from './home/TopGames.vue';

export default {
  	components:{ TrendingGames, Reviews, Recommendations, SiteStats, RecentUserActivity, TopGames },
    data() {
    	return {
    		games_count: 0,
    		users_count: 0,
    		reviews_count: 0,
    		recommendations_count: 0,
    		recent_user_activity: [],
    		ranked: [],
    		popular: [],
    		trending: []
    	}
    },
    computed: {
    	activityBanner() {
            return {
                backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(./images/banners/1.jpg)',                
                backgroundSize: 'cover',
                backgroundPosition: 'center', 
                backgroundRepeat: 'no-repeat',
                'background-attachment': 'fixed'
            }
    	},
    	bannerStyle() {
    		//random integer between 1 and 10
    		var randomInt = Math.floor(Math.random() * 10) + 1;
    		return {
                backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(https://mythril.nyc3.digitaloceanspaces.com/general/banners/'+ randomInt +'.jpg)', 
            }
    	}
    },
    created() {
		axios.get('/api/')
		.then((response) => { 
            this.games_count = response.data.games_count
            this.users_count = response.data.users_count
            this.reviews_count = response.data.reviews_count
            this.recommendations_count = response.data.recommendations_count
            this.recent_user_activity = response.data.recent_user_activity
            this.ranked = response.data.ranked
            this.popular = response.data.popular
            this.trending = response.data.trending
		})
		.catch((error) => console.log("Home values not updated."));
    }
}
</script>