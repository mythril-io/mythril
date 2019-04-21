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
                    <div class="control" v-for="tag in discussion.tags">
                        <div class="tags">
                            <router-link tag="span" :to="{name: 'Forums', params: { tag: tag.slug }}" :class="'tag is-dark underline-link '">{{ tag.name }}</router-link>
                        </div>
                    </div>

                    <div class="control" v-for="game in discussion.games">
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
                        <p>{{ discussion.body }}</p>
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
                        <p class="level-item"><a class="button">
                            <span class="icon is-small">
                                <i class="fas fa-reply"></i>
                            </span>
                        </a></p>
                        <p class="level-item"><a class="button is-danger is-outlined">
                            <span class="icon is-small">
                                <i class="fas fa-heart"></i>
                            </span>
                        </a></p>
                        <p class="level-item"><a class="button is-primary">Subscribe</a></p>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <section class="section">
        <div class="container">
            <div class="reply-placeholder  has-text-centered has-background-white	">
                <p class="heading is-size-7">Post a Reply</p>
            </div>
        </div>
    </section>

</div>
</template>

<script>
export default {
    data() {
        return {
            discussion: null
        };
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
    }
}
</script>

<style scoped>
.reply-placeholder {
    border: 2px dashed #fff;
    width: 55%;
    margin-right: auto;
    margin-left: auto;
    padding: 40px;
    border-radius: 4px;
}
.reply-placeholder:hover {
    border: 2px dashed #EEEEEE;
    cursor: pointer;
}
</style>