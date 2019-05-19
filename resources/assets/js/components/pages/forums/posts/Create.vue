<template>

<div>

    <transition name="fade" mode="out-in">
        <div class="columns is-mobile is-centered" v-if="!form" key="placeholder">
            <div class="column is-narrow" style="opacity: 0.6">
                <div style="width: 75px;">
                    <user-avatar 
                        :user="$store.state.user"
                        dimension="75px">
                    </user-avatar>
                </div>
            </div>
            <div class="column is-8">
                <div class="content">
                    <div @click="toggleForm()" class="reply-placeholder has-text-centered has-background-white">
                        <p class="heading is-size-7">Post a Reply...</p>
                    </div>
                </div>

            </div>
        </div>
    
        <div v-else key="form">
            <h2 class="subtitle">Post a Reply</h2>

            <div class="content" v-if="parentPost">
                <blockquote>
                    <nav class="level is-mobile" style="margin-bottom: 7px;">
                        <div class="level-left">
                            <p class="level-item">
                                <b-tooltip
                                    :label="parentPost.user.username"
                                    position="is-top"
                                    type="is-dark"
                                    animated> 
                                    <router-link 
                                        :to="{name: 'User', params: { id: parentPost.user.id }}" 
                                        class="image card is-32x32"
                                        :style="'background-size: cover; cursor:pointer; background-image: url('+ $store.state.userAvatarURL + parentPost.user.avatar +');'"
                                        >
                                    </router-link>
                                </b-tooltip>
                            <!-- <span class="image card is-32x32" style="background: url(http://oi64.tinypic.com/2yla7tx.jpg); background-size: cover;">
                            </span> -->
                            </p>
                            <p class="level-item">
                            <strong>{{ parentPost.user.username }}</strong>&nbsp;<small>{{ parentPost.created_at | ago($store.user)  }}</small>
                            </p>
                        </div>
                    <div class="level-right">
                        <p class="level-item">
                            <a class="button is-small" @click="$emit('onRemoveParentPost')">
                                <span class="icon is-small">
                                    <i class="fas fa-times"></i>
                                </span>
                            </a>
                        </p>
                    </div>
                    </nav>{{ parentPost.body }}
                        
                </blockquote>
            </div>

            <div class="field">
                <markdown-editor 
                    v-model="body" 
                    name="body"
                    v-validate="'required|min: 10'"
                    lockHeight="true"
                    :monitorHeight="maximized">
                </markdown-editor>
                <p v-show="errors.has('body')" class="help is-danger">{{ errors.first('body') }}</p>
            </div>

            <div class="field">
                <div class="control">
                    <button class="button is-primary" @click="validateBeforeSubmit" :disabled="errors.any()">Post Reply</button>
                </div>
            </div>

        </div>
    </transition>


    <!-- <hr v-if="discussion.posts.length == 0"> -->

    <!-- <div class="columns is-mobile is-centered">
        <div class="column is-narrow" style="opacity: 0.6">
            <div class="card" style="width: 75px;">
            <div class="card-image">
                <figure class="image is-1by1" style="background: url(https://mythril.nyc3.cdn.digitaloceanspaces.com/users/avatars/1.png); background-size: cover;">
                </figure>
            </div>
            </div>
        </div>
        <div class="column is-8">
            <div class="content">
                <div v-if="!form" @click="toggleForm()" class="reply-placeholder has-text-centered has-background-white">
                    <p class="heading is-size-7">Post a Reply...</p>
                </div>
                <div style="opacity: 0.6" v-else>
                    <strong>{{ $store.state.user.username }}</strong>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur repellendus asperiores, saepe magnam minima, veniam aliquam iste molestiae numquam aut assumenda libero deleniti cum commodi sint adipisci ad! Laborum, alias.</p>
                </div>
            </div>

        </div>
    </div> -->

    <!-- <transition name="slide">
        <div v-if="form" class="form-container" v-bind:class="{ 'minimized': minimized, 'maximized': maximized }">

            <div v-bind:class="[maximized ? 'maximized' : 'container']">

                <div class="minimized-form has-background-primary" @click="toggleMinimize()" v-if="minimized">
                    <span class="icon is-small" style="margin-right: 7px;">
                      <i class="fas fa-reply"></i>
                    </span>
                    Post a Reply - {{ discussion.title }}
                    <div class="buttons has-addons is-pulled-right">
                        <span class="button is-small is-white" @click="toggleForm()">
                            <span class="icon is-small">
                                <i class="fas fa-times"></i>
                            </span>
                        </span>
                    </div>
                </div>

                <div class="form card" v-bind:class="{ 'maximized': maximized }" v-show="!minimized">
                    <div class="buttons has-addons is-pulled-right">
                        <span class="button is-white" @click="toggleMinimize()" v-show="!maximized">
                            <span class="icon">
                                <i class="far fa-window-minimize"></i>
                            </span>
                        </span>
                        <span class="button is-white" @click="toggleMaximize()">
                            <span class="icon">
                                <i class="fas fa-expand"></i>
                            </span>
                        </span>
                        <span class="button is-white" @click="toggleForm()" v-show="!maximized">
                            <span class="icon">
                                <i class="fas fa-times"></i>
                            </span>
                        </span>
                    </div>

                    <h2 class="subtitle">Post a Reply</h2>

                    <div class="field">
                        <markdown-editor 
                            v-model="body" 
                            name="body"
                            v-validate="'required|min: 10'"
                            lockHeight="true"
                            :monitorHeight="maximized">
                        </markdown-editor>
                        <p v-show="errors.has('body')" class="help is-danger">{{ errors.first('body') }}</p>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button class="button is-primary" @click="validateBeforeSubmit" :disabled="errors.any()">Post Reply</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </transition> -->

</div>

</template>

<script>
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../utilities/AuthenticationModal.vue";
import MarkdownEditor from '../../../utilities/MarkdownEditor.vue'
import VeeValidate from 'vee-validate';
import NProgress from 'nprogress'
import UserAvatar from '../../components/UserAvatar.vue';

export default {
  components: {MarkdownEditor, UserAvatar},
  props: {
    discussion: {},
    parentPost: {},
    triggerForm: {},
    // form: {
    //   type: Boolean,
    //   default: false
    // }
  },
  data() {
    return {
        form: false,
        maximized: false,
        minimized: false,
        body: ''
    };
  },
  watch: {
    parentPost: function (post) {
      if(post!=null) {
          this.form = true
      }
    },
    triggerForm: function (boolean) {
      if(boolean) {
          this.form = true
      }
    }
  },
  methods: {
    toggleForm() {
        if (!this.$store.state.user) {
            ModalProgrammatic.open({
            parent: this,
            component: AuthenticationModal,
            hasModalCard: true
            })
        } else {
            this.form = !this.form;
        }
    },
    toggleMaximize() {
        this.maximized = !this.maximized;
    },
    toggleMinimize() {
        this.minimized = !this.minimized;
    },
    validateBeforeSubmit() {
        this.$validator.validateAll().then((result) => {
            if (result) { 
                var post = {
                    'discussion_id': this.discussion.id,
                    'body': this.body,
                }
                this.submitPost(post);
            }
        });
    },
    submitPost(post) {
        var success = true;
        NProgress.start();

        var parentPostId = null;
        if(this.parentPost != null) {
            parentPostId = this.parentPost.id
        }

        axios.post('/api/forums/posts', {
            body: post.body,
            discussion_id: this.discussion.id,
            parent_post_id: parentPostId
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
                    message: 'Post Not Created', 
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
                    message: 'Post Created', 
                    type: 'is-primary',
                })
                this.$emit('onAddPost', response)
                this.form = false;
                this.body = ''
            }
        });
    },
  },
  created() {

  }
};
</script>

<style scoped>
.reply-placeholder {
    border: 2px dashed #f6f6f6;
    /* width: 55%; */
    /* margin-right: auto;
    margin-left: auto; */
    padding: 27px;
    border-radius: 4px;
}
.reply-placeholder:hover {
    border: 2px dashed #EEEEEE;
    cursor: pointer;
}
.form-container {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 500;
}
.maximized {
    top: 0 !important;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100% !important;
}
.show-y-scroll {
	overflow-y: scroll;
}
.form {
    border-radius: 4px;
    padding: 20px;
    bottom: 0px;
    top: auto;
    height: 450px;
}
.minimized-form {
    border-radius: 4px 4px 0 0;
    padding: 10px;
    bottom: 0px;
    top: auto;
    color: #FFFFFF;
    /* background-color: #DBDBDB; */
}
.minimized-form:hover {
    background-color: #EEEEEE;
    cursor: pointer;
}
.slide-enter-active {
	-webkit-animation: slide-top 0.2s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
	        animation: slide-top 0.2s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
}
.slide-leave-active {
	-webkit-animation: slide-top 0.2s cubic-bezier(0.250, 0.460, 0.450, 0.940) both reverse;
	        animation: slide-top 0.2s cubic-bezier(0.250, 0.460, 0.450, 0.940) both reverse;
}
@keyframes slide-top {
  0% {
    -webkit-bottom: -500px;;
            bottom: -500px;;
  }
  100% {
    -webkit-bottom: 0px;;
            bottom: 0px;;
  }
}

</style>