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
                            <router-link tag="span" :to="{name: 'GameForums', params: { id: game.id }}" class="tag has-background-white-ter underline-link" style="padding-left: 0 !important">{{ game.title }}</router-link>
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
                    <user-avatar 
                        :user="discussion.user"
                        avatarSize="is-96x96">
                    </user-avatar>
                </div>
                <div class="column">
                    <div class="content">

                        <nav class="level is-mobile" style="margin-bottom: 7px;">
                            <div class="level-left">
                                <div class="level-item">
                                    <strong>{{ discussion.user.username }}</strong>
                                </div>
                                <div class="level-item">
                                    <small>{{ discussion.created_at | ago($store.user) }}</small>
                                </div>
                            </div>

                            <div class="level-right">
                                <p class="level-item">
                                    <small v-if="discussion.edit_count>0">
                                        <b-tooltip
                                            class="has-text-grey-light"
                                            :label="discussion.edited_at | ago($store.user)"
                                            position="is-top"
                                            type="is-dark"
                                            animated>
                                            <small>Edited</small>
                                        </b-tooltip>    
                                    </small>
                                </p>
                            </div>
                        </nav>
                            
                        <transition name="fade" mode="out-in">
                            <div v-if="!discussion.editing" key="readonly">
                                <p v-html="compiledMarkdown"></p>
                            </div>
                            <div v-else key="editing">
                                <b-field>
                                    <markdown-editor 
                                        v-model="discussion.editBody" 
                                        name="body"
                                        v-validate="'required|min: 10'"
                                        lockHeight="true"
                                        :disablePreview="true">
                                    </markdown-editor>
                                </b-field>
                                <b-field>
                                    <div class="buttons">
                                        <b-button type="is-primary" @click="editDiscussion(discussion)">Update</b-button>
                                        <b-button type="is-danger" @click="cancelEditing(discussion)">Cancel</b-button>
                                    </div>
                                </b-field>
                            </div>
                        </transition>
                        
                    </div>

                    <!-- Main container -->
                    <nav class="level is-mobile" v-show="!discussion.editing">
                        <!-- Left side -->
                        <div class="level-left">
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Replies</p>
                            <p class="title is-4">{{ discussion.post_count | numberFormatK }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Views</p>
                            <p class="title is-4">{{ discussion.view_count | numberFormatK }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Users</p>
                            <p class="title is-4">{{ discussion.user_count | numberFormatK }}</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                            <p class="heading">Likes</p>
                            <p class="title is-4">{{ discussion.like_count | numberFormatK }}</p>
                            </div>
                        </div>
                        </div>

                        <!-- Right side -->
                        <div class="level-right">                     
                        <p class="level-item" v-if="$store.state.user && ($store.state.user.id == discussion.user.id)">
                            <a class="button"
                                @click="startEditing(discussion)">
                                <span class="icon is-small">
                                    <i class="fas fa-edit"></i>
                                </span>
                            </a>
                        </p>
                        <p class="level-item">
                            <b-dropdown position="is-bottom-left">
                                <b-button
                                    slot="trigger"
                                    icon-pack="fas"
                                    icon-right="share-alt" />
                                <b-dropdown-item custom style="width:300px;">
                                    <b-field :label="discussion.title">
                                        <b-input :value="'http://mythril.test/forums/discussions/'+ discussion.id + '/' + discussion.slug"></b-input>
                                    </b-field>
                                        <social-sharing :url="'https://mythril.io/forums/discussions/' + discussion.id + '/' + discussion.slug"
                                                        :title="discussion.title"
                                                        :description="discussion.body.substring(0, 20)"
                                                        twitter-user="mythril_io"
                                                        inline-template>
                                        <div>
                                            <network network="twitter">
                                                    <b-button type="is-twitter"
                                                        icon-right="twitter" />
                                            </network>
                                            <network network="facebook">
                                                    <b-button type="is-facebook"
                                                        icon-right="facebook" />
                                            </network>
                                            <network network="reddit">
                                                    <b-button type="is-reddit"
                                                        icon-pack="fab"
                                                        icon-right="reddit-alien" />
                                            </network>
                                            <network network="whatsapp">
                                                    <b-button type="is-whatsapp"
                                                        icon-right="whatsapp" />
                                            </network>
                                        </div>
                                        </social-sharing>
                                </b-dropdown-item>
                            </b-dropdown>
                        </p>
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

</div>
</template>

<script>
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../../utilities/AuthenticationModal.vue";
import MarkdownEditor from '../../../../utilities/MarkdownEditor.vue'
import NProgress from 'nprogress'
import UserAvatar from '../../../components/UserAvatar.vue';

var md = require('markdown-it')();

export default {
    props: ["discussion"],
    components: {MarkdownEditor, UserAvatar},
    data() {
        return {
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
        },

        startEditing(discussion) {
            var discussionBodyCopy = _.cloneDeep(discussion.body);
            Vue.set(discussion, 'editing', true)
            Vue.set(discussion, 'editBody', discussionBodyCopy)
        },
        cancelEditing(discussion) {
            discussion.editing = false;
            discussion.editBody = null;
        },
        editDiscussion(discussion) {
            var success = true;
            NProgress.start();

			axios.patch(('/api/forums/discussions/' + discussion.id), {
				body: discussion.editBody,
			})
			.catch((error) => { 
                if(error.response.status === 403) { 
                    this.$snackbar.open({
                        message: error.response.data.error, 
                        type: 'is-danger',
                    })
                }
                else{ 
                    this.$snackbar.open({
                        message: 'Discussion Not Updated', 
                        type: 'is-danger',
                    })
                }

                NProgress.done();
                success = false;
			})
			.then((response) => {
                if(success) {
                    NProgress.done();
                    this.$snackbar.open({
                        message: 'Discussion Updated', 
                        type: 'is-primary',
                    })
                    discussion.body = discussion.editBody
                    discussion.updated_at = Date.now();
                    this.cancelEditing(discussion);
                }
			});
        },
    },
    created() {
    }
}
</script>