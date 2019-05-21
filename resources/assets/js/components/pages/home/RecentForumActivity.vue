<template>
<div>
    <div class="section-break"></div>
    <h1 class="title">Recent Forum Activity</h1>

    <b-table
    v-if="discussions"
        :data="discussions"
        ref="table"
        detailed
        detail-key="id"
        :opened-detailed="opened"
        :show-detail-icon="showDetailIcon">

        <template slot-scope="props">
            <b-table-column field="title" label="Title" sortable>
                <template v-if="showDetailIcon">
                    {{ props.row.title }}
                </template>
                <template v-else>
                    <a @click="toggle(props.row)">
                        {{ props.row.title }}
                    </a>
                </template>
            </b-table-column>

            <b-table-column label="Recent User">
                <router-link v-if="props.row.last_post" :to="{name: 'User', params: { id: props.row.last_post.user.id }}">{{ props.row.last_post.user.username }}</router-link>
                <router-link v-else :to="{name: 'User', params: { id: props.row.user.id }}">{{ props.row.user.username }}</router-link>
            </b-table-column>

            <b-table-column field="date" label="Recent Activity" width="150" centered>
                <span class="tag is-info" v-if="props.row.last_post">{{ props.row.last_post.created_at | ago($store.user)  }}</span>
                <span class="tag is-info" v-else>{{ props.row.created_at | ago($store.user)  }}</span>
            </b-table-column>

            <b-table-column field="date" label="Replies" width="40" centered>
                {{ props.row.post_count | numberFormatK }}
            </b-table-column>

            <b-table-column field="date" label="Views" width="40" centered>
                {{ props.row.view_count | numberFormatK }}
            </b-table-column>

        </template>

        <template slot="detail" slot-scope="props">
            <article class="media">
                <figure class="media-left">
                    <p class="image is-64x64">
                        <user-avatar 
                            :user="props.row.user"
                            avatarSize="is-64x64">
                        </user-avatar>
                    </p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>{{ props.row.title }}</strong>
                            <small>@<router-link :to="{name: 'User', params: { id: props.row.user.id }}">{{ props.row.user.username }}</router-link></small>
                            <small>{{ props.row.created_at | ago($store.user)  }}</small>
                            <br>{{ truncate(props.row.body) }}
                            <router-link :to="{name: 'Discussion', params: { id: props.row.id, slug: props.row.slug }}">[Read More]</router-link>
                        </p>
                        <div class="field is-grouped is-grouped-multiline">
                            <div class="control" v-for="tag in props.row.tags" :key="tag.id">
                                <div class="tags">
                                    <router-link tag="span" :to="{name: 'Forums', params: { tag: tag.slug }}" :class="'tag underline-link ' + tag.colour">{{ tag.name }}</router-link>
                                </div>
                            </div>

                            <div class="control" v-for="game in props.row.games" :key="game.id">
                                <div class="tags has-addons">
                                    <span class="tag has-background-grey-lighter">
                                        <b-icon
                                            icon="gamepad"
                                            size="is-small">
                                        </b-icon>
                                    </span>
                                    <router-link tag="span" :to="{name: 'GameForums', params: { id: game.id }}" class="tag has-background-grey-lighter underline-link" style="padding-left: 0 !important">{{ game.title }}</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </template>
    </b-table>

</div>
</template>

<script>
import UserAvatar from '../components/UserAvatar.vue';

export default {
    components: {UserAvatar},
    data() {
        return {
            discussions: null,
            showDetailIcon: true,
        };
    },
    computed: {
        opened() {
            return [this.discussions[0].id];
        }
    },
    methods: {
        toggle(row) {
            this.$refs.table.toggleDetails(row)
        },
        truncate(string) {
            return _.truncate(string, {
                    'length': 300,
                });
        },
        tags(array) {
            return _.join(array, ',');
        },
    },
    created() {
		axios.get('/api/forums')
		.then((response) => { 
			if(response.data === 404) { this.discussions = [] }
            this.discussions = response.data.data.slice(0, 5);
		})
        .catch((error) => this.discussions = [] );
    }
}

</script>