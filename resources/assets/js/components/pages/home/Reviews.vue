<template>
<div>
	
	<h1 class="title">Recent Game Reviews</h1>

	<div class="columns is-mobile is-multiline" v-if="reviews.length > 0">
	    <div class="column is-12" v-for="review in reviews.slice(0, 3)" :key="review.id">
	        <review-item :review="review"></review-item>
	    </div>
	</div>

    <div v-else>
    	<div class="notification">
    		There are no reviews in the database.
    	</div>
    </div>

</div>
</template>

<script>
import ReviewItem from '../components/ReviewItem.vue';

export default {
    components: { ReviewItem },
    data() {
    	return {
    		reviews: []
    	}
    },
    created() {
		axios.get('/api/reviews')
		.then((response) => { 
			this.reviews = response.data.data; 
		})
		.catch((error) => console.log("Reviews array not updated."));
    }
}
</script>