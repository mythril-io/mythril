<template>
<div>
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
			<h1 class="title has-text-centered-desktop">
				{{ this.recommendation.second_release.alternate_title ? this.recommendation.second_release.alternate_title : this.recommendation.second_game.title }} Recommendation
			</h1>
			<h2 class="subtitle has-text-centered-desktop">
				<span class="tag is-light"><a :href="'/games?platforms=[' + this.recommendation.second_release.platform.id + ']'" :title="this.recommendation.second_release.platform.name">{{ this.recommendation.second_release.platform.name }}</a></span>
				<span class="has-text-grey-dark is-size-6">
					&nbsp; Recommended by <router-link :to="{name: 'User', params: { id: this.recommendation.user.id }}" class="dotted-border">{{ this.recommendation.user.username }}</router-link> 
					on {{ dateFormat(this.recommendation.created_at) }}
				</span>
			</h2>
			<div class="buttons is-right" v-if="userOwns">
				<span class="button is-warning" @click="editMode=!editMode">
		            <span>{{ this.editMode ? "Exit Edit Mode" : "Edit" }}</span>
		            <span class="icon is-small">
		              <i class="fas fa-edit"></i>
		            </span>
		        </span>
				<span class="button is-danger" @click="deletePrompt=true">
		            <span>Delete</span>
		            <span class="icon is-small">
		              <i class="fas fa-trash-alt"></i>
		            </span>
		        </span>
	    	</div>

	    </div>
	  </div>
	</section>
	<section class="section">
		<div class="container">

			<delete-form 
				v-if="deletePrompt" 
				resource="recommendation" 
				@confirm="deleteRecommendation"
				@close="deletePrompt=!deletePrompt">
			</delete-form>

			<div v-if="editMode">
				<h2 class="title">Edit Mode</h2>
				<recommendation-form
					:recommendation="recommendation" 
					@submit="updateRecommendation">
				</recommendation-form>
			</div>

			<div v-if="!editMode">

				<div class="columns">
					<div class="column is-hidden-touch is-narrow recomendation-col is-clearfix">
			            <div class="image image-behind is-pulled-left card tooltip is-tooltip-primary" :data-tooltip="getFirstGameTitle">
			            	<router-link :to="{name: 'Game', params: { id: recommendation.game.id }}">
			            		<img v-lazy="this.$store.state.cdnURL + 'games/icons/' + recommendation.game.icon" :alt="getFirstGameTitle" class="image is-128x128">
			            	</router-link>
			            </div>
			            <div class="image recomendation-image is-pulled-right card tooltip is-tooltip-bottom is-tooltip-primary" :data-tooltip="getSecondGameTitle">
			            	<router-link :to="{name: 'Game', params: { id: recommendation.second_game.id }}">
			            		<img v-lazy="this.$store.state.cdnURL + 'games/icons/' + recommendation.second_game.icon" :alt="getSecondGameTitle" class="image is-128x128">
			            	</router-link>
			            </div>
					</div>
					<div class="column">
						<h2 class="subtitle">
							"If you liked 
							<router-link :to="{name: 'Game', params: { id: this.recommendation.game.id }}">{{ getFirstGameTitle }}</router-link> ({{ this.recommendation.release.platform.name }})
							 you may also like 
							 <router-link :to="{name: 'Game', params: { id: this.recommendation.second_game.id }}">{{ this.getSecondGameTitle }}</router-link> 
							 ({{ this.recommendation.second_release.platform.name }})."
						</h2>
						<hr>
						<div v-html="compiledMarkdown" class="content">
						</div>
					  	<div class="has-text-grey-light">
							Created On: {{ dateFormat(this.recommendation.created_at) }}<br>
							Last Updated: {{ dateFormat(this.recommendation.updated_at) }}
						</div>

					</div>
				</div>

			</div>
		</div>
	</section>

</div>
</template>

<script>
import NProgress from 'nprogress'
import RecommendationForm from './Form.vue'
import DeleteForm from '../../utilities/DeleteForm.vue'
var md = require('markdown-it')();
var moment = require('moment-timezone');

export default {
	props: [ 'user' ],
	components: {RecommendationForm, DeleteForm},
	data() {
		return {
		  recommendation: [],

		  userOwns: false,
		  editMode: false,
		  deletePrompt: false
		}
	},
	filters: {

	},
	watch: {
		user: function () { 
			if(!this.user) { 
				this.userOwns = false;
				this.editMode = false;
				this.deletePrompt = false;
			}
		}
	},
	computed: {
		compiledMarkdown: function () {
			return md.render(this.recommendation.content);
		},
		getFirstGameTitle(){
			var title = (this.recommendation.release.alternate_title ? this.recommendation.release.alternate_title : this.recommendation.game.title)
			return title;
		},
		getSecondGameTitle(){
			var title = (this.recommendation.second_release.alternate_title ? this.recommendation.second_release.alternate_title : this.recommendation.second_game.title)
			return title;
		}
	},
	methods: {
		dateFormat(date) {
			if(this.user) {
				if(this.user.timezone) {
					return moment.utc(date).tz(this.user.timezone).format('MMMM Do, YYYY');
				}				
			}
			return moment.utc(date).local().format('MMMM Do, YYYY');
		},
		updateRecommendation(recommendation) {
	        var success = true;
	        NProgress.start();
	        axios.patch(('/api/recommendations/' + this.recommendation.id), {
	        	content: recommendation.content
	        })
	        .catch((error) => { 
	        	if((error.response.status === 404) | (error.response.status === 403)) { flash(error.response.data.error, 'error') }
	        	else{ flash('Recommendation Not Updated', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
				  this.recommendation.content = recommendation.content;
				  	document.body.scrollTop = 0; // For Safari
				  	document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
				  this.editMode = false;
				  this.deletePrompt = false;
	              flash('Recommendation Updated', 'success');
	            }
	        });
		},
		deleteRecommendation() {
	        var success = true;
	        NProgress.start();
	        axios.delete('/api/recommendations/' + this.recommendation.id)
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Unable to Remove Recommendation', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	            	NProgress.done();
		    		flash('Recommendation Removed', 'success')
		    		this.$router.replace({ name: 'Recommendations'})
	            }
	        });
		}
	},
	created() {
		axios.get('/api/recommendations/' + this.$route.params.id )
		.then((response) => { 
			if(response.data === 404) { this.$router.replace({ name: 'Recommendations'}) }
			this.recommendation = response.data.recommendation; 
			this.userOwns = response.data.userOwns;
		})
		.catch((error) => this.$router.replace({ name: 'Recommendations'}) );
	}
}
</script>