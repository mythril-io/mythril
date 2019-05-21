<template>
<div>

    <nav class="level is-mobile">
        <div class="level-left">
            <p class="level-item" v-if="post.replies.length > 0">
                <button class="button is-light"  @click="showReplies = !showReplies">
                    <span>{{ post.replies.length }} {{ post.replies.length == 1 ? ' Reply' : ' Replies' }}</span>
                    <b-icon icon="arrow-down" pack="fas" size="is-small"></b-icon>
                </button>
            </p>
        </div>

        <div class="level-right">
            <p class="level-item">
                <span v-if="post.like_count" class="has-text-primary" style="margin-right: 4px; margin-top: 1px;">
                    {{ post.like_count }}
                </span>
                <a @click="toggleLike()">
                    <span class="icon is-small" style="margin-top: 7px;">
                        <i class="fa-heart" :class="{ 'far' : !post.has_liked, 'fas' : post.has_liked }"></i>
                    </span>
                </a>
            </p>
            <p class="level-item">
                <a @click="emitOnReply(post)">
                    <span class="icon is-small" style="margin-top: 7px;">
                        <i class="fas fa-reply"></i>
                    </span>
                </a>
            </p>
            <p class="level-item" v-if="$store.state.user && ($store.state.user.id == post.user.id)">
                <a @click="emitOnEdit(post)">
                    <span class="icon is-small" style="margin-top: 7px;">
                        <i class="fas fa-edit"></i>
                    </span>
                </a>
            </p>

        </div>
    </nav>

    <b-collapse :open.sync="showReplies" v-if="post.replies.length > 0">
        <div class="notification" style="margin-bottom: 7px;" v-for="reply in post.replies" :key="reply.id">
            <!-- <div v-if="reply.parent" class="notification is-white" style="margin: 0 15px 20px 15px;">
                <nav class="level is-mobile" style="margin-bottom: 7px;">
                    <div class="level-left">
                        <p class="level-item">
                            <b-tooltip
                                :label="reply.parent.user.username"
                                position="is-top"
                                type="is-dark"
                                animated> 
                                <router-link 
                                    :to="{name: 'User', params: { id: reply.parent.user.id }}" 
                                    class="image card is-32x32"
                                    :style="'background-size: cover; cursor:pointer; background-image: url('+ $store.state.userAvatarURL + reply.parent.user.avatar +');'"
                                    >
                                </router-link>
                            </b-tooltip>
                        </p>
                        <p class="level-item">
                            <strong>{{ reply.parent.user.username }}</strong>&nbsp;<small>{{ reply.parent.created_at | ago($store.user)  }}</small>
                        </p>
                    </div>
                <div class="level-right">
                </div>
                </nav>
                <span v-html="compiledMarkdown(reply.parent.body)"></span>
            </div> -->
            <nav class="level is-mobile" style="margin-bottom: 7px;">
                <div class="level-left">
                    <p class="level-item">
                        <user-avatar 
                            :user="reply.user"
                            avatarSize="is-32x32">
                        </user-avatar>
                    </p>
                    <p class="level-item">
                        <strong>{{ reply.user.username }}</strong>&nbsp;<small>{{ reply.created_at | ago($store.user)  }}</small>
                    </p>
                </div>
                <div class="level-right">
                    <p class="level-item">
                        <a class="button is-small" @click="findPost(reply.id)">
                            <span class="icon is-small">
                                <i class="fas fa-arrow-down"></i>
                            </span>
                        </a>
                    </p>
                </div>
            </nav>
            <span v-html="compiledMarkdown(reply.body)"></span>
        </div>
    </b-collapse>

</div>
</template>

<script>
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../utilities/AuthenticationModal.vue";
var md = require('markdown-it')();
import UserAvatar from '../../components/UserAvatar.vue';

export default {
    props: ["post"],
    components: {UserAvatar},
    data() {
        return {
            showReplies: false,
            liking: false,
        };
    },
    methods: {
        findPost(id) {
            this.$emit('onGoToPost', id)
        },
        emitOnReply(post) {
            if (!this.$store.state.user) {
                ModalProgrammatic.open({
                parent: this,
                component: AuthenticationModal,
                hasModalCard: true
                })
            } else {
                this.$emit('onReply', post)
            }            
        },
        emitOnEdit(post) {
            this.$emit('onEdit', post)
        },
        compiledMarkdown(content) {
            return md.render(content);
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
                axios.post('/api/forums/posts/like/' + this.post.id )
                .catch((error) => { 
                    if(error.response.status === 403) { flash(error.response.data.error, 'error') }
                    else{ 
                        this.$snackbar.open({
                            message: this.post.has_liked ? 'Unable to Remove Like' : 'Unable to Like', 
                            type: 'is-danger',
                        })
                    }
                    this.liking = false;
                })
                .then((response) => {
                    this.post.has_liked = response.data;
                    response.data ? this.post.like_count++ : this.post.like_count--;
                    this.liking = false;
                    this.$snackbar.open({
                        message: response.data ? 'Liked Post' : 'Removed Like', 
                        type: 'is-primary',
                    })
                });
            }
        },
    },
    created() {
    }
};
</script>