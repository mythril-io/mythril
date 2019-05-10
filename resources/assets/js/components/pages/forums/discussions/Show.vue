<template>
<div>

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
            <h1 class="title has-text-centered">
                {{ discussion.title }}
            </h1>
            <h2 class="subtitle has-text-centered">
                <div class="field is-grouped is-grouped-multiline centered-tags">
                    <div class="control" v-for="tag in discussion.tags" :key="tag.id">
                        <div class="tags">
                            <router-link tag="span" :to="{name: 'Forums', params: { tag: tag.slug }}" :class="'tag is-dark underline-link '">{{ tag.name }}</router-link>
                        </div>
                    </div>

                    <div class="control" v-for="game in discussion.games" :key="game.id">
                        <div class="tags has-addons">
                            <span class="tag">
                                <b-icon
                                    icon="gamepad"
                                    size="is-small">
                                </b-icon>
                            </span>
                            <router-link tag="span" :to="{name: 'Game', params: { id: game.id }}" class="tag has-background-white-ter underline-link" style="padding-left: 0 !important">{{ game.title }}</router-link>
                        </div>
                    </div>
                </div>
            </h2>
            </div>
        </div>
    </section>

    <section class="section has-background-white-bis" style="border-bottom: 2px #f5f5f5 solid;">
        <div class="container">
            <div class="columns bounce-enter-active">
                <div class="column is-narrow" style="width: 120px;">
                    <b-tooltip
                        :label="discussion.user.username"
                        position="is-top"
                        type="is-dark"
                        animated> 
                        <router-link 
                            tag="figure" 
                            :to="{name: 'User', params: { id: discussion.user.id }}" 
                            class="image card is-96x96"
                            :style="'background-size: cover; cursor:pointer; background-image: url('+ $store.state.userAvatarURL + discussion.user.avatar +');'"
                            >
                        </router-link>
                    </b-tooltip>

                </div>
                <div class="column">
                    <div class="content">
                        <div style="margin-bottom: 7px;"><strong>{{ discussion.user.username }}</strong> <small>{{ discussion.created_at | ago($store.user)  }}</small></div>
                        <p v-html="compiledMarkdown"></p>
                    </div>

                    <!-- Main container -->
                    <nav class="level is-mobile">
                        <!-- Left side -->
                        <div class="level-left">
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Replies</p>
                            <p class="title is-4">{{ discussion.post_count }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Views</p>
                            <p class="title is-4">{{ discussion.view_count }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Users</p>
                            <p class="title is-4">{{ discussion.user_count }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Likes</p>
                            <p class="title is-4">{{ discussion.like_count }}</p>
                            </div>
                        </div>
                        </div>

                        <!-- Right side -->
                        <div class="level-right">
                        <!-- <p class="level-item">
                            <a class="button" @click="quoteReply(discussion)">
                                <span class="icon is-small">
                                    <i class="fas fa-reply"></i>
                                </span>
                            </a>
                        </p> -->
                        <p class="level-item">
                            <a class="button is-danger"
                                :class="{ 'is-outlined' : !discussion.has_liked, 'is-loading' : liking }" 
                                @click="toggleLike()">
                                <span class="icon is-small">
                                    <i class="fas fa-heart"></i>
                                </span>
                            </a>
                        </p>
                        <p class="level-item">
                            <a class="button is-primary" 
                               :class="{ 'is-outlined' : !discussion.is_subscribed, 'is-loading' : subscribing }" 
                               @click="subscribe()">{{ discussion.is_subscribed ? 'Unsubscribe' : 'Subscribe'}}
                            </a>
                        </p>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <!-- Posts & Sidebar-->
    <section class="section" v-if="posts.data.length > 0">
        <div class="container">
            <div class="columns is-variable is-5">

                <!-- Posts -->
                <div class="column" id="posts">
                    <show-posts :posts="posts.data"></show-posts>
                </div>

                <!-- Sidebar-->
                <div class="column is-narrow" style="width: 200px;">
                    <affix class="sidebar-menu" relative-element-selector="#posts" style="width: 200px; margin-top: 10px;">
                        <sidebar :discussion="discussion" :posts="posts"></sidebar>
                    </affix>
                </div>

            </div>
        </div>
    </section>

    <!-- New Post -->
    <section class="section">
        <div class="container">
            <create-post :discussion="discussion"></create-post>
        </div>
    </section>

</div>
</template>

<script>
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../utilities/AuthenticationModal.vue";
import CreatePost from '../posts/Create.vue'
import ShowPosts from '../posts/Show.vue'
import Sidebar from './Sidebar.vue'
import { Affix } from 'vue-affix';

var md = require('markdown-it')();

export default {
  components: {CreatePost, ShowPosts, Sidebar, Affix},
    data() {
        return {
            discussion: null,
            posts: null,
            subscribing: false,
            liking: false,

        };
    },
	computed: {
		compiledMarkdown: function () {
			return md.render(this.discussion.body);
		}
	},
    methods: {
        quoteReply(discussion) {
            if (!this.$store.state.user) {
                ModalProgrammatic.open({
                parent: this,
                component: AuthenticationModal,
                hasModalCard: true
                })
            } else {
                //Pass the discussion (discussion id and username) to the create post component
                //Use @plugin to link bback to the original post
            }
        },
        toggleLike() {
            if (!this.$store.state.user) {
                ModalProgrammatic.open({
                parent: this,
                component: AuthenticationModal,
                hasModalCard: true
                })
            } else {
                this.liking = true;
                axios.post('/api/forums/discussions/like/' + this.discussion.id )
                .catch((error) => { 
                    if(error.response.status === 403) { flash(error.response.data.error, 'error') }
                    else{ 
                        this.$snackbar.open({
                            message: this.discussion.has_liked ? 'Unable to Remove Like' : 'Unable to Like', 
                            type: 'is-danger',
                        })
                    }
                    this.liking = false;
                })
                .then((response) => {
                    this.discussion.has_liked = response.data;
                    response.data ? this.discussion.like_count++ : this.discussion.like_count--;
                    this.liking = false;
                    this.$snackbar.open({
                        message: response.data ? 'Liked Discussion' : 'Removed Like', 
                        type: 'is-primary',
                    })
                });
            }
        },
        subscribe() {
            if (!this.$store.state.user) {
                ModalProgrammatic.open({
                parent: this,
                component: AuthenticationModal,
                hasModalCard: true
                })
            } else {
                this.subscribing = true;
                axios.post('/api/forums/discussions/subscribe/' + this.discussion.id )
                .catch((error) => { 
                    if(error.response.status === 403) { flash(error.response.data.error, 'error') }
                    else{ 
                        this.$snackbar.open({
                            message: this.discussion.is_subscribed ? 'Unable to Unsubscribe' : 'Unable to Subscribe', 
                            type: 'is-danger',
                        })
                    }
                    this.subscribing = false;
                })
                .then((response) => {
                    this.discussion.is_subscribed = response.data;
                    this.subscribing = false;
                    this.$snackbar.open({
                        message: (response.data ? 'Subscribed' : 'Unsubscribed') + ' to Discussion', 
                        type: 'is-primary',
                    })
                });
            }
        }
    },
    created() {
		axios.get('/api/forums/discussions/' + this.$route.params.id )
		.then((response) => { 
			if(response.data === 404) { this.$router.replace({ name: 'Forums'}) }
            this.discussion = response.data.discussion;
            if(this.$route.params.slug != response.data.discussion.slug) {
                this.$router.push({params: {slug: response.data.discussion.slug}}) 
            }
		})
        .catch((error) => this.$router.replace({ name: 'Forums'}) );
        
		axios.get('/api/forums/posts/' + this.$route.params.id )
		.then((response) => { 
            this.posts = response.data;
		})
		.catch((error) => this.$router.replace({ name: 'Forums'}) );
    }
}
</script>