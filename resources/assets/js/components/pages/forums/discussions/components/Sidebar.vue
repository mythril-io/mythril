<template>
    <div>
        <a class="button is-fullwidth is-primary" style="margin-bottom: 15px;" @click="emitOnReply">
            <span class="icon is-small">
                <i class="fas fa-reply-all"></i>
            </span>
            <span>Reply</span>
        </a>
        <router-link :to="{name: 'Discussion', params: { id: discussion.id, slug: discussion.slug, postNum: 1 }}" 
            class="button is-small is-rounded is-fullwidth is-light heading" 
            style="font-size: 11px;"
        >First Post</router-link>
        <vue-slider
            ref="posts"
            v-model="postNumber"
            direction="ttb"
            :tooltip="'always'"
            :tooltip-placement="'right'"
            dotSize="20"
            style="display: block; margin: 35px 0; height: 250px;"
            :process-style="{ backgroundColor: '#00d1b2' }"
            :min="min"
            :max="max"
            @click.native="emitOnFetchData"
            @drag-end="emitOnFetchData"
        >
            <template v-slot:dot="{ value, focus }">
                <div :class="['custom-dot', { focus }]"></div>
            </template>
            <template v-slot:tooltip="{ value, focus }">
                <div :class="[
                    'custom-tooltip', 
                    { focus }, 
                    'vue-slider-dot-tooltip-inner', 
                    'vue-slider-dot-tooltip-inner-right',
                    'pointer'
                ]">
                    {{ value }} of {{ max }} replies <br>
                    <!-- <small>Date</small> -->
                </div>
            </template>
        </vue-slider>
        <router-link :to="{name: 'Discussion', params: { id: discussion.id, slug: discussion.slug, postNum: max }}" 
            class="button is-small is-rounded is-fullwidth is-light heading" 
            style="font-size: 11px;"
        >Last Post</router-link>
    </div>
</template>

<script>
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../../utilities/AuthenticationModal.vue";
import VueSlider from 'vue-slider-component'
import 'vue-slider-component/theme/antd.css'

export default {
    props: ["discussion", "posts", "postNumber"],
    components: {VueSlider},
    data() {
        return {
            value: 1,
            min: 1
        };
    },
    computed: {
        max: function () {
            return this.posts.total;
        }
    },
    methods: {
        emitOnReply() {
            if (!this.$store.state.user) {
                ModalProgrammatic.open({
                parent: this,
                component: AuthenticationModal,
                hasModalCard: true
                })
            } else {
                this.$emit('onReply')
            }            
        },
        emitOnFetchData() {
            var minLoadedPost = _.minBy(this.posts.data, 'num');
            var maxLoadedPost = _.maxBy(this.posts.data, 'num');
            var postNum = this.$refs.posts.getValue()

            if(postNum <= maxLoadedPost.num && postNum >= minLoadedPost.num) {
                this.$emit('onScrollToPost', postNum)
            } else {
                var page = Math.ceil(postNum/5);
                this.$emit('onFetchPosts', page)
                this.$emit('onChangePostNumber', postNum)
            }           
        }
    },
    created() {
        this.max = this.posts.total;
    }
}
</script>

<style>
.custom-dot {
    width: 100%;
    height: 100%;
    border-radius: 4px;
    background-color: #00d1b2;
    transition: all .3s;
}
.custom-dot:hover {
    /* transform: rotateZ(45deg); */
    cursor: move;
}
.custom-dot.focus {
    border-radius: 50%;
}
.pointer {
    cursor: move;
}
</style>