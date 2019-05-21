<template>
<div>
    <div v-for="post in posts" :key="post.id" :index="post.num" v-view="viewHandler">
        <div class="columns" :id="'post' + post.num" >
            <div class="column is-narrow">
                <user-avatar 
                    :user="post.user"
                    avatarSize="is-64x64">
                </user-avatar>
            </div>
            <div class="column">
                <div class="content">
                    <nav class="level is-mobile" style="margin-bottom: 7px;">
                        <div class="level-left">
                            <div class="level-item">
                                <strong>{{ post.user.username }}</strong>
                            </div>
                            <div class="level-item">
                                <small>{{ post.created_at | ago($store.user)  }}</small>
                            </div>
                        </div>
                        <div class="level-right">
                            <p class="level-item">
                                <small v-if="post.edit_count>0">
                                    <b-tooltip
                                        class="has-text-grey-light"
                                        :label="post.updated_at | ago($store.user)"
                                        position="is-top"
                                        type="is-dark"
                                        animated>
                                        <small>Edited</small>
                                    </b-tooltip>    
                                </small>
                            </p>
                            <p class="level-item">
                                <b-dropdown position="is-bottom-left">
                                    <a slot="trigger"><small>#{{ post.num }}</small></a>
                                    <b-dropdown-item custom style="width:300px;">
                                        <b-field :label="'Post #' + post.num">
                                            <b-input :value="'https://mythril.io/forums/discussions/' + discussion.id + '/' + discussion.slug + '/' + post.num"></b-input>
                                        </b-field>
                                            <social-sharing :url="'https://mythril.io/forums/discussions/' + discussion.id + '/' + discussion.slug + '/' + post.num"
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
                        </div>
                    </nav>
                    <b-collapse :open="false" v-if="post.parent != null">
                        <button class="button is-light" slot="trigger" style="margin-bottom: 7px;">
                            <b-icon icon="reply" pack="fas" size="is-small"></b-icon>
                            <span>{{ post.parent.user.username }}</span>
                        </button>

                        <div class="notification" style="margin-bottom: 7px;">
                            <div v-if="post.parent.parent" class="notification is-white" style="margin: 0 15px 20px 15px;">
                                <nav class="level is-mobile" style="margin-bottom: 7px;">
                                <div class="level-left">
                                    <p class="level-item">
                                        <user-avatar 
                                            :user="post.parent.parent.user"
                                            avatarSize="is-32x32">
                                        </user-avatar>
                                    </p>
                                    <p class="level-item">
                                        <strong>{{ post.parent.parent.user.username }}</strong>&nbsp;<small>{{ post.parent.parent.created_at | ago($store.user)  }}</small>
                                    </p>
                                </div>
                                <div class="level-right">
                                    <!-- <p class="level-item">
                                        <a class="button is-small is-light">
                                            <span class="icon is-small">
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        </a>
                                    </p> -->
                                    <p class="level-item">
                                        <a class="button is-small is-light" @click="findPost(post.parent.parent.id)">
                                            <span class="icon is-small">
                                                <i class="fas fa-arrow-up"></i>
                                            </span>
                                        </a>
                                    </p>
                                </div>
                                </nav>
                                <span v-html="compiledMarkdown(post.parent.parent.body)"></span>
                            </div>

                            <nav class="level is-mobile" style="margin-bottom: 7px;">
                                <div class="level-left">
                                    <p class="level-item">
                                        <user-avatar 
                                            :user="post.parent.user"
                                            avatarSize="is-32x32">
                                        </user-avatar>
                                    </p>
                                    <p class="level-item">
                                        <strong>{{ post.parent.user.username }}</strong>&nbsp;<small>{{ post.parent.created_at | ago($store.user)  }}</small>
                                    </p>
                                </div>
                            <div class="level-right">
                                <!-- <p class="level-item">
                                    <a class="button is-small">
                                        <span class="icon is-small">
                                            <i class="fas fa-chevron-down"></i>
                                        </span>
                                    </a>
                                </p> -->
                                <p class="level-item">
                                    <a class="button is-small" @click="findPost(post.parent.id)">
                                        <span class="icon is-small">
                                            <i class="fas fa-arrow-up"></i>
                                        </span>
                                    </a>
                                </p>
                            </div>
                            </nav>
                            <span v-html="compiledMarkdown(post.parent.body)"></span>

                        </div>
                    </b-collapse>
                    <transition name="fade" mode="out-in">
                        <div v-if="!post.editing" key="readonly">
                            <p v-html="compiledMarkdown(post.body)"></p>
                        </div>
                        <div v-else key="editing">
                            <b-field>
                                <markdown-editor 
                                    v-model="post.editBody" 
                                    name="body"
                                    v-validate="'required|min: 10'"
                                    lockHeight="true"
                                    :monitorHeight="maximized"
                                    :disablePreview="true">
                                </markdown-editor>
                            </b-field>
                            <b-field>
                                <div class="buttons">
                                    <b-button type="is-primary" @click="editPost(post)">Update</b-button>
                                    <b-button type="is-danger" @click="cancelEditing(post)">Cancel</b-button>
                                </div>
                            </b-field>
                        </div>
                    </transition>
                    
                </div>

                <post-footer 
                    :post="post" 
                    @onEdit="startEditing(post)"
                    @onReply="emitOnReply(post)"
                    @onGoToPost="findPost"
                ></post-footer>

            </div>
        </div>
        <hr>
    </div> 
</div>
</template>

<script>
import PostFooter from './PostFooter.vue'
import NProgress from 'nprogress'
import MarkdownEditor from '../../../utilities/MarkdownEditor.vue'
import UserAvatar from '../../components/UserAvatar.vue';

var md = require('markdown-it')();

export default {
    props: ['posts', 'scrollTo', 'discussion'],
    components: {PostFooter, MarkdownEditor, UserAvatar},
    data() {
        return {
            editing: false,
        };
    },
    computed: {
        // compiledMarkdown: function (content) {
        //     return md.render(content);
        // }
    },
    watch: {
        scrollTo: function () {
            this.scrollToPost(this.scrollTo)
        },
    },
    methods: {
        emitOnReply(post) {
            this.$emit('onReply', post)
        },
        compiledMarkdown(content) {
            return md.render(content);
        },
        viewHandler(node) {
            if(node.percentCenter > 0.25 && node.percentCenter < 0.35) {
                this.$emit('onViewPost', node.target.element.childNodes[0].id.substring(4));
            }
        },
        findPost(id) {
            this.$emit('onFindPost', id)
        },
        startEditing(post) {
            //make backup of post
            var postBodyCopy = _.cloneDeep(post.body);
            Vue.set(post, 'editing', true)
            Vue.set(post, 'editBody', postBodyCopy)
            // this.editing = true;
        },
        cancelEditing(post) {
            post.editing = false;
            post.editBody = null;
        },
        editPost(post) {
            var success = true;
            NProgress.start();

			axios.patch(('/api/forums/post/' + post.id), {
				body: post.editBody,
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
                        message: 'Post Not Updated', 
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
                        message: 'Post Updated', 
                        type: 'is-primary',
                    })
                    post.body = post.editBody
                    post.updated_at = Date.now();
                    this.cancelEditing(post);
                }
			});
        },
        scrollToPost(postNum) {
            this.$scrollTo("#post" + postNum, 500, {
                offset: -150
            });
        }
    },
    created() {  
    },
};
</script>
