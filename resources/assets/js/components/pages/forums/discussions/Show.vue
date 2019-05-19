<template>
<div>

    <!-- Discussion Info -->
    <discussion 
        :discussion="discussion"
    ></discussion>

    <!-- Posts & Sidebar-->
    <section class="section" v-if="posts.data && posts.data.length > 0">
        <div class="container">
            <div class="columns is-variable is-5">

                <!-- Loader -->
                <b-loading :is-full-page="false" :active.sync="isLoadingPosts"></b-loading>

                <!-- Posts -->
                <div class="column" id="posts" v-show="!isLoadingPosts">
                    <button 
                        v-if="firstPage > 1"
                        class="button is-light is-small heading is-rounded" 
                        style="font-size: 11px; width: 50%; margin-left: auto; margin-right: auto"
                        @click="getPreviousPage"
                    >Load Previous</button>
                    <show-posts 
                        :posts="posts.data"
                        :scrollTo="scrollTo"
                        @onReply="setParentPost"
                        @onViewPost="setViewPost"
                    >
                    </show-posts>
                    <button 
                        v-if="lastPage < posts.last_page"
                        class="button is-light is-small heading is-rounded" 
                        style="font-size: 11px; width: 50%; margin-left: auto; margin-right: auto"
                        @click="getNextPage"
                    >Load More</button>
                </div>
                

                <!-- Sidebar-->
                <div class="column is-narrow is-hidden-mobile" style="width: 200px;">
                    <affix class="sidebar-menu" relative-element-selector="#posts" style="width: 200px; margin-top: 10px;">
                        <sidebar 
                            :discussion="discussion" 
                            :posts="posts"
                            :postNumber="postNumber"
                            @onReply="setParentPost"
                            @onChangePostNumber="setViewPost"
                            @onScrollToPost="scrollToPost"
                            @onFetchPosts="setInitialPosts"
                        >
                        </sidebar>
                    </affix>
                </div>

            </div>
        </div>
    </section>

    <!-- New Post -->
    <section class="section" id="reply-form">
        <div class="container">
            <create-post 
                :discussion="discussion" 
                :parentPost="parentPost"
                :triggerForm="triggerForm"
                @onRemoveParentPost="setParentPost(null)"
                @onAddPost="addReply"
            >
            </create-post>
        </div>
    </section>

</div>
</template>

<script>
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../utilities/AuthenticationModal.vue";
import NProgress from 'nprogress'

import Discussion from './components/Discussion.vue'
import Sidebar from './components/Sidebar.vue'
import { Affix } from 'vue-affix';

import ShowPosts from '../posts/Show.vue'
import CreatePost from '../posts/Create.vue'
import MarkdownEditor from '../../../utilities/MarkdownEditor.vue'

var md = require('markdown-it')();

export default {
    components: {CreatePost, ShowPosts, Sidebar, Affix, MarkdownEditor, Discussion},
    data() {
        return {
            discussion: null,
            posts: null,
            parentPost: null,
            triggerForm: false,

            firstPage: 1,
            lastPage: 1,
            postNumber: 1,

            isLoadingPosts: true,
            scrollTo: null
        };
    },
    methods: {
        addReply(post) {
            //this.posts.push(post);
            //Go to last page
            this.setParentPost(null);
        },
        setParentPost(post=null) {
            this.parentPost = post;
            this.triggerForm = true;
            this.$scrollTo("#reply-form");
        },
        setViewPost(postNumber) {
            this.postNumber = postNumber;
        },
        scrollToPost(postNumber) {
            this.scrollTo = postNumber;
        },
        setInitialPosts(page = 1) {
            this.isLoadingPosts = true;
            axios.get('/api/forums/posts/' + this.$route.params.id + '?page=' + page )
            .then((response) => { 
                this.posts = response.data;
                this.firstPage = response.data.current_page;
                this.lastPage = response.data.current_page;
                this.isLoadingPosts = false;
            })
            .catch((error) => {
                this.isLoadingPosts = false; 
            });

            if(this.$route.params.postNum) {
                this.scrollToPost(this.$route.params.postNum)
            }
        },
        getPreviousPage(page = null) {
            if(page != null) {
                page = --this.firstPage;
                axios.get('/api/forums/posts/' + this.$route.params.id + '?page=' + page )
                .then((response) => { 
                    this.posts.data = _.concat(response.data.data, this.posts.data)                

                    this.firstPage = response.data.current_page;
                    this.posts.last_page = response.data.last_page;
                    this.posts.total = response.data.total;
                })
                .catch((error) => console.log("Previous page not loaded") );
            }
        },
        getNextPage(page = null) {
            if(page != null) {
                page = ++this.lastPage;
                axios.get('/api/forums/posts/' + this.$route.params.id + '?page=' + page )
                .then((response) => { 
                    this.posts.data = _.concat(this.posts.data, response.data.data)
                    this.lastPage = response.data.current_page;

                    this.posts.last_page = response.data.last_page;
                    this.posts.total = response.data.total;

                })
                .catch((error) => console.log("Next page not loaded") );
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

        var page = 1;
        if(this.$route.params.postNum) {
            page = Math.ceil(this.$route.params.postNum/5);
        }

        this.setInitialPosts(page);
    }
}
</script>