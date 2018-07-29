<template>
	<section class="section">
		<div class="container">
			
			<div class="columns is-multiline">
				<div class="column is-one-fifth is-hidden-mobile">
					<img v-lazy="'https://mythril.nyc3.digitaloceanspaces.com/general/link.png'" alt="Reviews" class="imageFade">
				</div>
				<div class="column">
				    <h1 class="title">Add a Review</h1>

					<div class="notification is-primary">
						<h2 class="subtitle">Step 1: Select a Game</h2>
				      	<div class="field">
						  <p class="control">
						    <multiselect
						      v-model="selectedGame"
						      :options="games"
						      :close-on-select="true"
						      placeholder="Select a Game"
						      track-by="title"
						      label="title"
						      class="select" name="selectedGame">
						    </multiselect>
						  </p>
						</div>
					</div>

					<div class="notification is-warning" v-if="selectedGame">
						<h2 class="subtitle">Step 2: Select a Release</h2>
				      	<div class="field">
							<div class="control">
								<div class="select is-fullwidth">
								  <select v-model="selectedRelease">
								    <option :value="null">Select a Release</option>
									<option v-for="release in this.releases" :value="release">
									  {{ release.platform.name }}
									  {{ release.alternate_title ? ('(' + release.alternate_title + ')') : "" }}
									  | Publisher: {{ release.publisher.name }}
									  | Region: {{ release.region.name }}
									</option>
								  </select>
								</div>
							</div>
						</div>
					</div>

		    	</div>
			    <div class="column is-12" v-show="showReviewForm">

					<review-form 
						@submit="addReview">
					</review-form>

			    </div>
			</div>

		</div>
	</section>
</template>

<script>
import NProgress from 'nprogress'
import Multiselect from 'vue-multiselect'
import ReviewForm from './Form.vue'

export default {
	components: {Multiselect, ReviewForm},
	data() {
    	return {
    		games: [],
    		releases: [],

    		selectedGame: null,
    		selectedRelease: null,
    		showReviewForm: false
    	}
	},
	watch: {
	  selectedGame: function () {
	    if(this.selectedGame == null) { 
	    	this.releases = null;
	    	this.selectedRelease = null;
	    	this.showReviewForm = false;
	    }
	    else { 
	    	this.selectedRelease = null;
	    	this.showReviewForm = false;
			axios.get('/api/games/' + this.selectedGame.id)
	        .then((response) => { this.releases = response.data.releases; })
	        .catch((error) => console.log("Releases array not updated."));
	    }
	  },
	  selectedRelease: function () {
	    if(this.selectedRelease == null) { this.showReviewForm = false}
	    else { this.showReviewForm = true }
	  }
	},
	created() {
		axios.get('/api/allgames')
        .then((response) => { this.games = response.data; })
        .catch((error) => console.log("Games array not updated."));

        if(this.$route.query.game) {
        	//find game in this.games
        	//set selectedGame
        }
	},
	methods: {
      	addReview(review){
      		var success = true;
	        NProgress.start();
	        axios.post('/api/reviews/', {
	        	game_id: this.selectedGame.id,
	        	release_id: this.selectedRelease.id,
	        	summary: review.summary,
	        	content: review.content,
	        	score: review.score
	        })
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Review Not Added', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Review Added', 'success');
	              const id = response.data.id
	              this.$router.push({ name: 'Review', params: { id }})
	              //this.$router.push({ path: `/reviews/${response.data.id}` })
	              response.data.id
	            }
	        });
      	}

	}
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>