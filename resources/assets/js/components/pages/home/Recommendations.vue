<template>
<div>
	
	<h1 class="title">Recent Game Recommendations</h1>

	<div class="columns is-multiline is-centered" v-if="recommendations.length > 0">
	    <div class="column is-12-tablet is-6-desktop is-12-widescreen" v-for="recommendation in recommendations.slice(0, 3)" :key="recommendation.id" >
            <div style="padding: 10px 20px 0 20px;">
                <recommendation-item :recommendation="recommendation"></recommendation-item>
            </div>
	        
	    </div>
	</div>

    <div v-else>
    	<div class="notification">
    		There are no recommendations in the database.
    	</div>
    </div>

</div>
</template>

<script>
import RecommendationItem from '../components/RecommendationItem.vue';

export default {
    components: { RecommendationItem },
    data() {
    	return {
    		recommendations: []
    	}
    },
    mounted() {
		axios.get('/api/recommendations')
		.then((response) => { 
			this.recommendations = response.data.data; 
		})
		.catch((error) => console.log("Recommendations array not updated."));
    }
}
</script>