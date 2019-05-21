<template>
	<div>
		<div class="content">
			<h2>Reviews</h2>
		</div>

	    <article class="message" v-if="game.reviews_count > 0">
	      <div class="message-body">
	        Currently, there are <strong>{{ game.reviews_count }}</strong> reviews in the database. <strong><router-link :to="{name: 'CreateReview', query: { game: game.id }}">Write a Review</router-link></strong>
	      </div>
	    </article>

			<div style="position: relative; min-height: 100px;" v-if="reviewsLoading">
				<b-loading :is-full-page="false" active="true"></b-loading>
			</div>

		<div v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" :infinite-scroll-immediate-check="false">
			<div v-if="!reviewsLoading">
				<reviews-component :reviews="game.reviews" showUserAvatar="true"></reviews-component>
			</div>

			<div v-if="newReviewsLoading" class="columns">
				<div class="column">
					<b-loading :is-full-page="false" active="true"></b-loading>
				</div>
			</div>

		</div>
	</div>
</template>

<script>
import ReviewsComponent from '../../components/ReviewsComponent.vue';

export default {
    props: ['game'],
    components: {ReviewsComponent},
	data() {
		return {
		  reviews: [],
		  currentPage: 0,
		  lastPage: -1,

		  reviewsLoading: true,
		  newReviewsLoading: false,

		  busy: true,
		}
	},
	methods: {
		loadMore: function() {
	      this.busy = true;
	      setTimeout(() => {
			if(this.currentPage < this.lastPage) 
			{ 
				this.newReviewsLoading = true;
				this.currentPage++

				var query = '?page=' + (this.currentPage)

				axios.get('/api/games/'+ this.$route.params.id + '/reviews' + query)
			    .then((response) => { 
			    	this.reviews.push(response.data.data[0])
			    	this.newReviewsLoading = false; 
			    })  
			}
			this.busy = false;	
	      }, 500);
	    }
	},
	created() {
		axios.get('/api/games/'+ this.$route.params.id + '/reviews')
		.then((response) => { 
			this.reviews = response.data.data; 
			this.currentPage = response.data.current_page; 
			this.lastPage = response.data.last_page; 
			this.reviewsLoading = false; 
		})
		.catch((error) => console.log("Reviews array not updated."));

		setTimeout(() => { this.busy = false; }, 2500);
	}
}	
</script>