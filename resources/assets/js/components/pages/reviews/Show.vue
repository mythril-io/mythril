<template>
<div>
	<section class="hero is-primary">
	  <div class="hero-body">
	    <div class="container">
			<h1 class="title has-text-centered-desktop">
				{{ this.review.release.alternate_title ? this.review.release.alternate_title : this.review.game.title }} Review
			</h1>
			<h2 class="subtitle has-text-centered-desktop">
				<span class="tag is-light"><a :href="'/games?platforms=[' + this.review.release.platform.id + ']'" :title="this.review.release.platform.name">{{ this.review.release.platform.name }}</a></span>
				<span class="has-text-grey-dark is-size-6">
					&nbsp; Reviewed by <router-link :to="{name: 'User', params: { id: this.review.user.id }}" class="dotted-border">{{ this.review.user.username }}</router-link> 
					on {{ dateFormat(this.review.created_at) }}
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
				resource="review" 
				@confirm="deleteReview"
				@close="deletePrompt=!deletePrompt">
			</delete-form>

			<div v-if="editMode">
				<h2 class="title">Edit Mode</h2>
				<review-form
					:review="review" 
					@submit="updateReview">
				</review-form>
			</div>

			<div v-if="!editMode">
			
		    	<div class="columns">
		    		<div class="column is-2 is-hidden-touch">
						<b-tooltip :label="this.review.game.title">
							<figure class="card image imageFade">
								<div class="text-container">
									<router-link :to="{name: 'Game', params: { id: this.review.game.id }}">
										<img v-lazy="this.$store.state.cdnURL + 'games/icons/' + this.review.game.icon" class="imageFade" :alt="this.review.game.title">
									</router-link>
								</div>
							</figure>
						</b-tooltip>
		    		</div>
		    		<div class="column">
				    	<h2 class="title">Summary/<b-tooltip label="Too Long; Didn't Read" type="is-light" position="is-bottom" dashed>TL;DR</b-tooltip></h2>
						<div class="notification is-warning review-summary">
							{{ this.review.summary }}
						</div>
		    		</div>
		    	</div>

				<hr>
				<div v-html="compiledMarkdown" class="content">
				</div>
				<div class="columns">
					<div class="column is-narrow">

					<div class="notification is-primary">
						<h1 class="title is-1">{{ this.review.score }}%</h1>
					</div>
					</div>
					<div class="column">
						<span v-if="this.totalLikeDislike == 0">
							0 users liked this review.
						</span>
						<span v-else>
							{{ this.likes }} out of {{ this.totalLikeDislike }} users liked this review.
						</span>
						
						<a class="button is-small is-primary" v-bind:class="{ 'is-outlined': !userLikes }" @click="likeGuard">
							<span class="icon is-small">
							  <i class="fas fa-angle-up"></i>
							</span>
						</a>
						<a class="button is-small is-danger" v-bind:class="{ 'is-outlined': !userDislikes }" @click="dislikeGuard">
							<span class="icon is-small">
							  <i class="fas fa-angle-down"></i>
							</span>
						</a>
						<br><br>
					  	<div class="has-text-grey-light">
							Created On: {{ dateFormat(this.review.created_at) }}<br>
							Last Updated: {{ dateFormat(this.review.updated_at) }}
						</div>

					</div>
				</div>

			</div>
		</div>
	</section>
	<authentication-modal :modalState="authModalState" v-on:close="toggleModal('auth')"></authentication-modal>	 

</div>
</template>

<script>
import NProgress from 'nprogress'
import ReviewForm from './Form.vue'
import DeleteForm from '../../utilities/DeleteForm.vue'
import AuthenticationModal from '../../utilities/AuthenticationModal.vue';
var md = require('markdown-it')();
var moment = require('moment-timezone');

export default {
	props: [ 'user' ],
	components: {ReviewForm, DeleteForm, AuthenticationModal},
	data() {
		return {
		  review: [],
		  authenticationModal: false,

		  likes: 0,
		  dislikes: 0,
		  userLikes: false,
		  userDislikes: false,

		  userOwns: false,
		  editMode: false,
		  deletePrompt: false
		}
	},
	computed: {
		compiledMarkdown: function () {
			return md.render(this.review.content);
		},
	    authModalState() {
	    	return this.authenticationModal;
	    },
	    totalLikeDislike() {
	    	return (this.likes + this.dislikes);
	    }
	},
	watch: {
		user: function () { 
			if(!this.user) { 
				this.userOwns = false;
				this.userLikes = false,
		  		this.userDislikes = false,
				this.editMode = false;
				this.deletePrompt = false;
			} else { this.checkUserLikeDislike() }
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
		updateReview(review) {
	        var success = true;
	        NProgress.start();
	        axios.patch(('/api/reviews/' + this.review.id), {
	        	content: review.content,
	        	summary: review.summary,
	        	score: review.score,
	        })
	        .catch((error) => { 
	        	if((error.response.status === 404) | (error.response.status === 403)) { flash(error.response.data.error, 'error') }
	        	else{ flash('Review Not Updated', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
				  this.review.content = review.content;
				  this.review.summary = review.summary;
				  this.review.score = review.score;
				  	document.body.scrollTop = 0; // For Safari
				  	document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
				  this.editMode = false;
				  this.deletePrompt = false;
	              flash('Review Updated', 'success');
	            }
	        });
		},
		deleteReview() {
	        var success = true;
	        NProgress.start();
	        axios.delete('/api/reviews/' + this.review.id)
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Unable to Remove Review', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	            	NProgress.done();
		    		flash('Review Removed', 'success')
		    		this.$router.replace({ name: 'Reviews'})
	            }
	        });
		},
	    toggleModal(modal) {
	        if(modal == 'auth') { this.authenticationModal = !this.authenticationModal; }
	    },
	  	likeGuard() {
	  		if(!this.user) { this.toggleModal('auth') }
	  		else if(this.userOwns) { flash('Not Allowed To Like Own Review', 'error') }
	  		else { this.toggleLike() }
	  	},
	  	dislikeGuard() {
	  		if(!this.user) { this.toggleModal('auth') }
	  		else if(this.userOwns) { flash('Not Allowed To Dislike Own Review', 'error') }
	  		else { this.toggleDislike() }
	  	},
		toggleLike() {
			//axios post to like review
      		var success = true;
	        axios.post('/api/reviews/like/', { review_id: this.review.id })
	        .catch((error) => { 
	        	if((error.response.status === 403) || (error.response.status === 404)) { flash(error.response.data.error, 'error') }
	        	else{ flash('Could Not Like Review', 'error') }
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              this.likes = response.data.likes; 
	              this.dislikes = response.data.dislikes; 
	              this.userLikes = response.data.userLikes;
	              this.userDislikes = response.data.userDislikes;
	            }
	        });
		},
		toggleDislike() {
			//axios post to like review
      		var success = true;
	        axios.post('/api/reviews/dislike/', { review_id: this.review.id })
	        .catch((error) => { 
	        	if((error.response.status === 403) || (error.response.status === 404)) { flash(error.response.data.error, 'error') }
	        	else{ flash('Could Not Dislike Review', 'error') }
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              this.likes = response.data.likes; 
	              this.dislikes = response.data.dislikes; 
	              this.userLikes = response.data.userLikes;
	              this.userDislikes = response.data.userDislikes;
	            }
	        });
		},
		checkUserLikeDislike() {
			if(this.user) {
	      		var success = true;
		        axios.get('/api/reviews/' + this.$route.params.id + '/user/')
		        .catch((error) => { 
		            success = false;
		        })
		        .then((response) => {
		            if(success) {
						this.userLikes = response.data.userLikes;
				        this.userDislikes = response.data.userDislikes;
		            }
		        });
			}
		}
	},
	created() {
		axios.get('/api/reviews/' + this.$route.params.id )
		.then((response) => { 
			if(response.data === 404) { this.$router.replace({ name: 'Reviews'}) }
			this.review = response.data.review; 
			this.userOwns = response.data.userOwns;
			this.likes = this.review.like_count;
			this.dislikes = this.review.dislike_count;
		})
		.catch((error) => this.$router.replace({ name: 'Reviews'}) );

		this.checkUserLikeDislike();
	}
}
</script>