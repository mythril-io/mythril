<template>
<div>
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
			<h1 class="title has-text-centered-desktop">
				User Recommendations
			</h1>
			<h2 class="subtitle has-text-centered-desktop">
				<span class="has-text-grey-dark is-size-6">
					Looking for a game that's similar to what you've played? 
				</span>
			</h2>
	    </div>
	  </div>
	</section>
	<section class="section">
		<div class="container">
			
		    <div v-if="loading" class="columns">
		    	<div class="column">
		    		<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
		    	</div>
			</div>
			<div v-else-if="recommendations.length > 0">

					<article class="message">
					  <div class="message-body">
					    Currently, there are <strong>{{ total }}</strong> recommendations in the database. <strong><router-link :to="{name: 'CreateRecommendation'}">Write a Recommendation</router-link></strong>
					  </div>
					</article>

			    	<recommendations-component :recommendations="recommendations"></recommendations-component>

				<bulma-paginate :pages="pages" :initial-page="pageIndex" @paginate="changePage"></bulma-paginate>
			</div>

			<div class="columns is-multiline is-centered" v-else>
		    	<div class="column is-12">
		    		<div class="notification is-warning" >
						Currently, there are no recommendations in the database. Be the first to write one! <router-link :to="{name: 'CreateRecommendation'}" >Write a Recommendation</router-link>
					</div>
		    	</div>
			</div>

		</div>
	</section>

</div>
</template>

<script>
import NProgress from 'nprogress'
import ReviewForm from './Form.vue'
import DeleteForm from '../../utilities/DeleteForm.vue'
var moment = require('moment-timezone');
import RecommendationsComponent from '../components/RecommendationsComponent.vue';

export default {
	props: [ 'user' ],
	components: {ReviewForm, DeleteForm, RecommendationsComponent},
	data() {
		return {
		  recommendations: [],
		  pageIndex: 1,
		  total: 0,
		  pages: 0,
		  loading: true
		}
	},
	filters: {
		truncate: function(string, value) {
    		return string.substring(0, value) + '...';
    	}
	},
	methods: {
		getRecommendations(page = 1) {
			axios.get('/api/recommendations?page=' + page)
			.then((response) => { 
				if(response.data.data.length == 0) { this.$router.push({ name: 'Recommendations'}) }
				this.recommendations = response.data.data; 
				this.total = response.data.total; 
				this.pages = response.data.last_page; 
				this.loading = false; 
			})
			.catch((error) => console.log("Recommendations array not updated."));
		},
		changePage(pageNum) { 
			this.$router.push({ name: 'Recommendations', query: { page: pageNum }})
		},
		dateFormat(date) {
			if(this.user) {
				if(this.user.timezone) {
					return moment.utc(date).tz(this.user.timezone).format('MMMM Do, YYYY');
				}				
			}
			return moment.utc(date).local().format('MMMM Do, YYYY');
		}
	},
	created() {
		if(this.$route.query.page) { var page = Number(this.$route.query.page)}
		else { var page = 1}

		this.pageIndex = (page - 1)
		this.getRecommendations(page);
		
	}
}
</script>