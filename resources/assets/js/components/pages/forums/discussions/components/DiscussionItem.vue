<template>
<div>
    <div class="notification border-hover" style="margin-bottom: 10px;">
        <nav class="level is-mobile">
            <div class="level-left">
                <div class="level-item has-text-centered is-hidden-touch">
                    <user-avatar 
                        :user="discussion.user"
                        avatarSize="is-64x64">
                    </user-avatar>
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
                    <p class="heading">
                        <router-link v-if="last_post" :to="{name: 'User', params: { id: discussion.last_post.user.id }}" class="underline-link">{{ discussion.last_post.user.username }}</router-link>
                        <router-link v-else :to="{name: 'User', params: { id: discussion.user.id }}" class="underline-link">{{ discussion.user.username }}</router-link>
                        - {{ discussion.last_posted_at | ago($store.user)  }}
                    </p>

                    <div class="field is-grouped is-grouped-multiline">
                        <div class="control" v-for="tag in discussion.tags" :key="tag.id">
                            <div class="tags">
                                <router-link tag="span" :to="{name: 'Forums', params: { tag: tag.slug }}" :class="'tag underline-link ' + tag.colour">{{ tag.name }}</router-link>
                            </div>
                        </div>

                        <div class="control" v-for="game in discussion.games" :key="game.id">
                            <div class="tags has-addons">
                                <span class="tag has-background-grey-lighter">
                                    <b-icon
                                        icon="gamepad"
                                        size="is-small">
                                    </b-icon>
                                </span>
                                <router-link tag="span" :to="{name: 'GameForums', params: { id: game.id }}" class="tag underline-link has-background-grey-lighter" style="padding-left: 0 !important">{{ game.title }}</router-link>
                            </div>
                        </div>
                    </div>
                        
                    </div>
                </div>
            </div>

            <div class="level-right is-hidden-touch">
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
                    <p class="heading">Likes</p>
                    <p class="title is-4">{{ discussion.like_count | numberFormatK }}</p>
                    </div>
                </div>
            </div>
        </nav>
    </div>

</diV>

</template>

<script>
import UserAvatar from '../../../components/UserAvatar.vue';

export default {
    props: ["discussion"],
    components: {UserAvatar},
    data() {
        return {
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