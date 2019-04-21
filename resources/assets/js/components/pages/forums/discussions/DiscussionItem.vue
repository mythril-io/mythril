<template>

<!-- <div class="message border-hover">
    <div class="message-body">
    <nav class="level is-mobile">
        <div class="level-left">
        <div class="level-item has-text-centered">
            <div>
            <figure class="image card is-64x64" style="background-size: cover; background-image: url(https://mythril.nyc3.cdn.digitaloceanspaces.com/users/avatars/1.png);">
            </figure>
            </div>
        </div>
        <div class="level-item">
            <div>
            <p class="title is-6 has-text-grey-dark" >
                <a href="/thread.html" style="text-decoration: none;">Lorem ipsum dolor sit amet</a>
            </p>
            <p class="heading has-text-grey"><a style="text-decoration: none;">John Doe</a> replied 2 days ago</p>
            <div class="tags">
                <span class="tag is-primary">Games</span>
                <span class="tag has-background-grey-lighter">Final Fantasy VII</span>
            </div>
            </div>
        </div>

        </div>

        <div class="level-right">
        <div class="level-item has-text-centered">
            <div>
            <p class="heading">Replies</p>
            <p class="title is-4">34</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading">Views</p>
            <p class="title is-4">60</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
            <p class="heading">Likes</p>
            <p class="title is-4">15</p>
            </div>
        </div>
        <div class="level-item">
            <span class="icon"
                style="margin-left: 15px; margin-right: -10px; background-color: hsl(48, 100%, 67%); 
                        border: none; border-radius: 290486px; width: 35px; height: 35px;"
            >
            <i class="fas fa-thumbtack"></i>
            </span>
        </div>
        </div>
    </nav>

    </div>
</div> -->
<div>
<!-- <div class="notification border-hover" style="margin-bottom: 10px;">

    <div class="columns is-vcentered is-paddingless	is-variable is-2">
        <div class="column is-narrow">
            <figure class="image card is-64x64" :style="'background-size: cover; background-image: url('+ $store.state.userAvatarURL + discussion.user.avatar +');'">
            </figure>
        </div>
        <div class="column">

            <div>
            <p class="title is-6 has-text-grey-dark is-marginless">


        <b-tooltip
            :label="discussionBody"
            position="is-bottom"
            type="is-dark"
            size="is-large"
            multilined
            animated> 
            <a href="/thread.html" style="text-decoration: none;">{{ discussion.title }}</a>
        </b-tooltip>

            </p>
            <p class="heading"><a href="#" style="text-decoration: none;">{{ discussion.user.username }}</a> replied {{ discussion.created_at | ago($store.user)  }}</p>

                <div class="field is-grouped is-grouped-multiline">
                    <div class="control" v-for="tag in discussion.tags">
                        <div class="tags">
                            <span :class="'tag ' + tag.colour">{{ tag.name}} </span>
                        </div>
                    </div>

                    <div class="control" v-for="game in discussion.games">
                        <div class="tags">
                            <span class="tag has-background-grey-lighter">{{ game.title}} </span>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
        <div class="column is-narrow">
            <nav class="level is-mobile">
                <div class="level-right">
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
                        <p class="heading">Likes</p>
                        <p class="title is-4">{{ discussion.like_count }}</p>
                        </div>
                    </div>
                </div>
            </nav>
        </div>

    </div>

</div> -->



<div class="notification border-hover" style="margin-bottom: 10px;">
    <nav class="level">
        <div class="level-left">
        <div class="level-item has-text-centered is-hidden-touch">
            <b-tooltip
                :label="discussion.user.username"
                position="is-top"
                type="is-light"
                animated> 
                <router-link 
                    tag="figure" 
                    :to="{name: 'User', params: { id: discussion.user.id }}" 
                    class="image card is-64x64"
                    :style="'background-size: cover; cursor:pointer; background-image: url('+ $store.state.userAvatarURL + discussion.user.avatar +');'"
                    >
                </router-link>
            </b-tooltip>
        </div>
        <div class="level-item">
            <div>
            <p class="title is-6 has-text-grey-dark ">


        <b-tooltip
            :label="discussion.body|truncate(200)"
            position="is-bottom"
            type="is-dark"
            size="is-large"
            multilined
            animated> 
            <router-link :to="{name: 'Discussion', params: { id: discussion.id, slug: discussion.slug }}" style="text-decoration: none;">{{ discussion.title }}</router-link>
        </b-tooltip>

            </p>
            <p class="heading"><router-link :to="{name: 'User', params: { id: discussion.user.id }}" class="underline-link">{{ discussion.user.username }}</router-link> replied {{ discussion.created_at | ago($store.user)  }}</p>

                <div class="field is-grouped is-grouped-multiline">
                    <div class="control" v-for="tag in discussion.tags">
                        <div class="tags">
                            <router-link tag="span" :to="{name: 'Forums', params: { tag: tag.slug }}" :class="'tag underline-link ' + tag.colour">{{ tag.name }}</router-link>
                        </div>
                    </div>

                    <div class="control" v-for="game in discussion.games">
                        <div class="tags has-addons">
                            <span class="tag has-background-grey-lighter">
                                <b-icon
                                    icon="gamepad"
                                    size="is-small">
                                </b-icon>
                            </span>
                            <router-link tag="span" :to="{name: 'Game', params: { id: game.id }}" class="tag underline-link has-background-grey-lighter" style="padding-left: 0 !important">{{ game.title }}</router-link>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        </div>

        <!-- Right side -->
        <div class="level-right is-hidden-touch">
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
                <p class="heading">Likes</p>
                <p class="title is-4">{{ discussion.like_count }}</p>
                </div>
            </div>
        </div>
    </nav>

</div>

</diV>

</template>

<script>
export default {
    props: ["discussion"],
    data() {
        return {
            // tags: []
        };
    },
    created() {
    }
}
</script>
<style scoped>
.tag-link {
    cursor: pointer;
}
.tag-link:hover {
    background-color: rgba(219, 219, 219, 0.5) !important;
}

</style>