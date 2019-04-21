<template>
<div>
  <div class="review-background" :style="bannerStyle(review.game.banner)">
  </div>

  <section class="section review-body">
    <div class="container">
			<h1 class="title has-text-centered has-text-white-bis">
				<router-link :to="{name: 'Game', params: { id: this.review.game.id }}" class="has-text-white-bis">{{ this.review.release.alternate_title ? this.review.release.alternate_title : this.review.game.title }}</router-link> Review
				</h1>
			<h2 class="subtitle has-text-centered">
				<div class="field is-grouped is-grouped-multiline centered-tags">
					<div class="control">
						<div class="tags has-addons">
							<span class="tag underline-link"><a class="has-text-grey-dark" :href="'/games?platforms=[' + this.review.release.platform.id + ']'" :title="this.review.release.platform.name">{{ this.review.release.platform.name }}</a></span>
						</div>
					</div>
				
					<div class="control">
						<div class="tags has-addons">
							<span class="tag underline-link"><router-link :to="{name: 'User', params: { id: this.review.user.id }}" class="has-text-grey-dark">{{ this.review.user.username }}</router-link></span>
							<span class="tag is-primary">{{ dateFormat(this.review.created_at) }}</span>
						</div>
					</div>
				</div>
			</h2>

      <div class="card" style="margin-top: 6rem; border-radius: 4px;">
        <div class="card-content">
              
          <div class="columns is-vcentered is-mobile">
            <div class="column is-narrow is-hidden-touch" style="z-index: 2; width: 200px;">
							<user-avatar :user="this.review.user" style="min-height: 176px" />
            </div>
            <div class="column margin-left" style="margin-right: -50px; z-index: 1;">
                    <div class="notification padding-left" style="padding-right: 50px; min-height: 134px;">
                <div class="content">
                    <h2 class="title has-text-weight-light">Summary/<b-tooltip label="Too Long; Didn't Read" type="is-primary" dashed>TL;DR</b-tooltip></h2>
										{{ this.review.summary }}
                </div>
                </div>
            </div>
            <div class="column is-narrow" style="z-index: 2">
               <div class="notification is-primary" style="padding: 1.25rem 2rem 1.25rem 2rem"><h1 class="title is-1 has-text-weight-semibold">{{ this.review.score }}%</h1></div>
            </div>
          </div>
          
          <div class="is-clearfix">
              <div class="is-pulled-right">
								<span v-if="this.totalLikeDislike == 0">
									0 users liked this review.
								</span>
								<span v-else>
									{{ this.likes }} out of {{ this.totalLikeDislike }} users liked this review.
								</span>
								<a class="button is-small is-primary" v-bind:class="{ 'is-outlined': !userLikes }" @click="likeGuard">
									<span class="icon is-small">
										<i class="fas fa-thumbs-up"></i>
									</span>
								</a>
								<a class="button is-small is-danger" v-bind:class="{ 'is-outlined': !userDislikes }" @click="dislikeGuard">
									<span class="icon is-small">
										<i class="fas fa-thumbs-down"></i>
									</span>
								</a>
              </div>
          </div>
          <hr>

          <p class="content" v-html="compiledMarkdown">
            <div class="has-text-grey-light">Last Updated: {{ dateFormat(this.review.updated_at) }}</div>
          </p>
        </div>
      </div>

  </div>  
  </section>


</div>
</template>

<script>
import NProgress from 'nprogress'
import ReviewForm from './Form.vue'
import DeleteForm from '../../utilities/DeleteForm.vue'
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from '../../utilities/AuthenticationModal.vue';
import UserAvatar from '../components/UserAvatar.vue';
var md = require('markdown-it')();
var moment = require('moment-timezone');

export default {
	props: [ 'user' ],
	components: {ReviewForm, DeleteForm, AuthenticationModal, UserAvatar},
	data() {
		return {
		  review: null,

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
		likeGuard() {
			if(!this.user) { 
				ModalProgrammatic.open({
				parent: this,
				component: AuthenticationModal,
				hasModalCard: true
				})	  
			}
			else if(this.userOwns) { flash('Not Allowed To Like Own Review', 'error') }
			else { this.toggleLike() }
		},
		dislikeGuard() {
			if(!this.user) { 
				ModalProgrammatic.open({
				parent: this,
				component: AuthenticationModal,
				hasModalCard: true
				})	  
			}
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
		},
    avatarStyle(avatar) {
      if (avatar) {
        return {
					backgroundImage:
						"url(" + this.$store.state.cdnURL + "users/avatars/" + avatar + ")",
					backgroundSize: "cover",
					backgroundPosition: "center top",
					backgroundRepeat: "no-repeat",
					width: "100%",
					height: "100%"
				};
			} 
			else {
				return {
					backgroundImage:
						"url(" + this.$store.state.cdnURL + "users/avatars/default.jpg)",
					backgroundSize: "cover",
					backgroundPosition: "center top",
					backgroundRepeat: "no-repeat",
					width: "100%",
					height: "100%"
				};
			}
    },
    bannerStyle(banner) {
      return {
        backgroundImage:
          "linear-gradient(rgba(0, 0, 0, 0.55), rgba(0, 0, 0, 0.55))," +
          "url(" + this.$store.state.cdnURL + "games/banners/" +  banner +  ")"
      };
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

<style scoped>
	@media (min-width: 1088px) {
		.padding-left {
			padding-left: 50px;
		}
		.margin-left {
			margin-left: -50px;
		}
	}
	.review-background {
		background-size: cover;
		background-position: center;
		background-repeat: no-repeat;
		height: 30rem;
		background-attachment: fixed;
	}
	.review-body {
		margin-top: -25rem; 
	}
</style>