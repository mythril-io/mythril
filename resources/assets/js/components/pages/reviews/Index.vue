<template>
<div>
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
			<h1 class="title has-text-centered-desktop">
				User Reviews
			</h1>
			<h2 class="subtitle has-text-centered-desktop">
				<span class="has-text-grey-dark is-size-6">
					Wondering if a game is worth playing? 
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
			<div v-else-if="reviews.length > 0">

				<article class="message">
				  <div class="message-body">
				    Currently, there are <strong>{{ total }}</strong> reviews in the database. <strong><router-link :to="{name: 'CreateReview'}">Write a Review</router-link></strong>
				  </div>
				</article>

				<reviews-component :reviews="reviews"></reviews-component>

				<bulma-paginate :pages="pages" :initial-page="pageIndex" @paginate="changePage"></bulma-paginate>
			</div>

			<div class="columns is-multiline is-centered" v-else>
		    	<div class="column is-12">
		    		<div class="notification is-warning" >
						Currently, there are no reviews in the database. Be the first to write one! <router-link :to="{name: 'CreateReview'}" >Write a Review</router-link>
					</div>
		    	</div>
			</div>

		</div>
	</section>

</div>
</template>

<script>
import ReviewsComponent from '../components/ReviewsComponent.vue';
var moment = require('moment-timezone');

export default {
	props: [ 'user' ],
	components: {ReviewsComponent},
	data() {
		return {
		  reviews: [],
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
		getReviews(page = 1) {
			axios.get('/api/reviews?page=' + page)
			.then((response) => { 
				if(response.data.data.length == 0) { this.$router.push({ name: 'Reviews'}) }
				this.reviews = response.data.data; 
				this.total = response.data.total; 
				this.pages = response.data.last_page; 
				this.loading = false; 
			})
			.catch((error) => console.log("Reviews array not updated."));
		},
		changePage(pageNum) { 
			//this.getReviews(pageNum) 
			this.$router.push({ name: 'Reviews', query: { page: pageNum }})
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
		this.getReviews(page);
		
	}
}
</script>