<template>
<div>
    <div class="content">
        <h2>Reviews</h2>
    </div>

		<div v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" :infinite-scroll-immediate-check="false">

			<div v-if="reviewsLoading">
				<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
			</div>
			<div v-else>
				<reviews-component 
					:reviews="reviews" 
					hideUser="true"
					customMessage="No reviews found."
				></reviews-component>
		  </div>

			<div v-if="newReviewsLoading" class="columns">
				<div class="column">
					<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
				</div>
			</div>

		</div>
</div>
</template>

<script>
import ReviewsComponent from '../../components/ReviewsComponent.vue';

export default {
    props: ['displayedUser'],
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

				axios.get('/api/users/'+ this.$route.params.id + '/reviews' + query)
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
		axios.get('/api/users/'+ this.$route.params.id + '/reviews')
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