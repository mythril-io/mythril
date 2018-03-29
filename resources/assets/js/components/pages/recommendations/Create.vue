<template>
	<section class="section">
		<div class="container">
			<h1 class="title">Add a Recommendation</h1>

			<div class="columns is-multiline">

				<div class="column">
					<h1 class="title is-5">If you like this game ...</h1>
					<div class="notification is-primary">
						<h2 class="subtitle">Select a Game</h2>
				      	<div class="field">
						  <p class="control">
						    <multiselect
						      v-model="selectedGameOne"
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

					<div class="notification is-warning" v-if="selectedGameOne">
						<h2 class="subtitle">Select a Release</h2>
				      	<div class="field">
							<div class="control">
								<div class="select is-fullwidth">
								  <select v-model="selectedReleaseOne">
								    <option :value="null">Select a Release</option>
									<option v-for="release in this.releasesOne" :value="release">
									  {{ release.platform.name }}
									  {{ release.alternate_title ? ('(' + release.alternate_title + ')') : "" }}
									  | Publisher: {{ release.publisher.name }}
									  {{ release['NA'] ? "[NA]" : "" }}
									  {{ release['JP'] ? "[JP]" : "" }}
									  {{ release['EU'] ? "[EU]" : "" }}
									  {{ release['WW'] ? "[WW]" : "" }}
									</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
		    	</div>

				<div class="is-divider-vertical is-hidden-mobile" data-content="AND"></div>
				<div class="is-divider is-hidden-tablet" data-content="AND"></div>

				<div class="column">
					<h1 class="title is-5">... then you might like this game.</h1>
					<div class="notification is-primary">
						<h2 class="subtitle">Select Recommended Game</h2>
				      	<div class="field">
						  <p class="control">
						    <multiselect
						      v-model="selectedGameTwo"
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

					<div class="notification is-warning" v-if="selectedGameTwo">
						<h2 class="subtitle">Select Recommended Release</h2>
				      	<div class="field">
							<div class="control">
								<div class="select is-fullwidth">
								  <select v-model="selectedReleaseTwo">
								    <option :value="null">Select a Release</option>
									<option v-for="release in this.releasesTwo" :value="release">
									  {{ release.platform.name }}
									  {{ release.alternate_title ? ('(' + release.alternate_title + ')') : "" }}
									  | Publisher: {{ release.publisher.name }}
									  {{ release['NA'] ? "[NA]" : "" }}
									  {{ release['JP'] ? "[JP]" : "" }}
									  {{ release['EU'] ? "[EU]" : "" }}
									  {{ release['WW'] ? "[WW]" : "" }}
									</option>
								  </select>
								</div>
							</div>
						</div>
					</div>
		    	</div>
				
			    <div class="column is-12" v-show="showRecommendationForm">
					<div class="is-divider" data-content="THEN"></div>
					<review-form 
						@submit="addRecommendation">
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

    		selectedGameOne: null,
    		selectedReleaseOne: null,
    		releasesOne: [],


    		selectedGameTwo: null,
    		selectedReleaseTwo: null,
			releasesTwo: [],

			showRecommendationForm: false
    	}
	},
	watch: {
	  selectedGameOne: function () {
	    if(this.selectedGameOne == null) { 
	    	this.releasesOne = null;
	    	this.selectedReleaseOne = null;
	    }
	    else if (this.selectedGameOne && this.selectedGameTwo) {
	    	if(this.selectedGameOne.id == this.selectedGameTwo.id) {
				setTimeout(() => {
					flash('Please Select Two Different Games', 'error')
					this.selectedGameOne = null;
					this.selectedGameTwo = null;
				}, 50);
	    	} else {
		    	this.selectedReleaseOne = null;
				axios.get('/api/games/' + this.selectedGameOne.id)
		        .then((response) => { this.releasesOne = response.data.releases; })
		        .catch((error) => console.log("Releases One array not updated."));
	    	}
	    }
	    else { 
	    	this.selectedReleaseOne = null;
			axios.get('/api/games/' + this.selectedGameOne.id)
	        .then((response) => { this.releasesOne = response.data.releases; })
	        .catch((error) => console.log("Releases One array not updated."));
	    }
	  },
	  selectedGameTwo: function () {
	    if(this.selectedGameTwo == null) { 
	    	this.releasesTwo = null;
	    	this.selectedReleaseTwo = null;
	    }
	    else if (this.selectedGameTwo && this.selectedGameOne) {
	    	if(this.selectedGameTwo.id == this.selectedGameOne.id) {
				setTimeout(() => {
					flash('Please Select Two Different Games', 'error')
					this.selectedGameOne = null;
					this.selectedGameTwo = null;
				}, 50);
	    	} else {
		    	this.selectedReleaseTwo = null;
				axios.get('/api/games/' + this.selectedGameTwo.id)
		        .then((response) => { this.releasesTwo = response.data.releases; })
		        .catch((error) => console.log("Releases Two array not updated."));
	    	}
	    }
	    else { 
	    	this.selectedReleaseTwo = null;
			axios.get('/api/games/' + this.selectedGameTwo.id)
	        .then((response) => { this.releasesTwo = response.data.releases; })
	        .catch((error) => console.log("Releases Two array not updated."));
	    }
	  },
	  selectedReleaseOne: function () {
	    if(this.selectedReleaseOne == null) { this.showRecommendationForm = false}
	    else if ((this.selectedReleaseOne != null) && (this.selectedReleaseTwo != null)) { this.showRecommendationForm = true }
	  },
	  selectedReleaseTwo: function () {
	    if(this.selectedReleaseTwo == null) { this.showRecommendationForm = false}
	    else if ((this.selectedReleaseOne != null) && (this.selectedReleaseTwo != null)) { this.showRecommendationForm = true }
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
      	addRecommendation(recommendation){
      		var success = true;
	        NProgress.start();
	        axios.post('/api/recommendations/', {
	        	game_id: this.selectedGameOne.id,
	        	release_id: this.selectedReleaseOne.id,
	        	second_game_id: this.selectedGameTwo.id,
	        	second_release_id: this.selectedReleaseTwo.id,

	        	content: recommendation.content
	        })
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Recommendation Not Added', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Recommendation Added', 'success');
	              const id = response.data.id
	              this.$router.push({ name: 'Recommendation', params: { id }})
	            }
	        });
      	}

	}
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>