<template>
<div>
 	<div class="content">
			<h2>Recommendations</h2>
	</div>

	<div style="position: relative; min-height: 100px;" v-if="recommendationsLoading">
		<b-loading :is-full-page="false" active="true"></b-loading>
	</div>

	<div v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" :infinite-scroll-immediate-check="false">

		<div v-if="!recommendationsLoading">
			<recommendations-component :recommendations="recommendations"></recommendations-component>
		</div>

		<div v-if="newRecommendationsLoading" class="columns">
			<div class="column">
				<b-loading :is-full-page="false" active="true"></b-loading>
			</div>
		</div>

	</div>
</div>
</template>

<script>
import RecommendationsComponent from '../../components/RecommendationsComponent.vue';

export default {
    props: ['game', 'displayedUser'],
    components: {RecommendationsComponent},
	data() {
		return {
		  recommendations: [],
		  currentPage: 0,
		  lastPage: -1,

		  recommendationsLoading: true,
		  newRecommendationsLoading: false,

		  busy: true,
		}
	},
	methods: {
		loadMore: function() {
	      this.busy = true;
	      setTimeout(() => {
			if(this.currentPage < this.lastPage) 
			{ 
				this.newRecommendationsLoading = true;
				this.currentPage++

				var query = '?page=' + (this.currentPage)

				axios.get('/api/games/'+ this.$route.params.id + '/recommendations' + query)
			    .then((response) => { 
			    	this.recommendations.push(response.data.data[0])
			    	this.newRecommendationsLoading = false; 
			    })  
			}
			this.busy = false;	
	      }, 500);
	    }
	},
	created() {
		axios.get('/api/games/'+ this.$route.params.id + '/recommendations')
		.then((response) => { 
			this.recommendations = response.data.data; 
			this.currentPage = response.data.current_page; 
			this.lastPage = response.data.last_page; 
			this.recommendationsLoading = false; 
		})
		.catch((error) => console.log("Recommendations array not updated."));

		setTimeout(() => { this.busy = false; }, 2500);
	}
}	
</script>