<template>
	<div>
        <nav class="level">
            <!-- Left side -->
            <div class="level-left">
            </div>

            <!-- Right side -->
            <div class="level-right">
                <div class="field is-grouped is-grouped-multiline">
                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">Score</span>
                            <span class="tag is-primary"><b>{{ this.game.score ? (this.game.score + '%') : 'N/A' }}</b></span>
                        </div>
                    </div>

                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">Ranked</span>
                            <span class="tag is-light"><b>{{ this.game.score_rank ? ('#' + this.game.score_rank) : 'N/A' }}</b></span>
                        </div>
                    </div>

                    <div class="control">
                        <div class="tags has-addons">
                            <span class="tag is-dark">Popularity</span>
                            <span class="tag is-light"><b>{{ this.game.popularity_rank ? ('#' + this.game.popularity_rank) : 'N/A' }}</b></span>
                        </div>
                    </div>

                </div>
            </div>
        </nav>
        <progress class="progress is-primary is-small" :value="this.game.score ? this.game.score : 0" max="100" id="animationProgress">{{ this.game.score ? this.game.score : 0 }}%</progress>

        <div class="content">
            <h2>Description</h2>
            <p>{{ this.game.synopsis }}</p>
        </div>

        <div class="content">
            <h2>Recent Reviews</h2>
        </div>
            
            <reviews-component :reviews="game.reviews" :game="game"></reviews-component>
            <br>

        <div class="content">
            <h2>Recommendations</h2>
        </div>

        <!--<div class="notification has-text-centered">-->
        <!--<blockquote>-->
            <div class="columns is-multiline is-mobile" v-if="topRecommendations.length > 0">
                <div class="column is-narrow" v-for="game in topRecommendations.splice(0,6)" style="min-width: 140px; max-width: 175px">
                    <div class="image card tooltip is-tooltip-primary item-shadow" :data-tooltip="game[0].second_game.title">
                        <div class="text-container">
                            <router-link :to="{name: 'Game', params: { id: game[0].second_game_id }}">
                                <div class="icon-text">
                                    {{ game.length }} User(s)
                                    <span class="is-hidden-tablet"> - {{ game[0].second_game.title }}</span>
                                </div>
                                <img :src="getIcon(game[0].second_game.icon)">
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <article class="message is-warning" v-else>
              <div class="message-body">
                No recommendations found. Be the first to write one! <router-link :to="{name: 'CreateRecommendation'}" >Write a Recommendation</router-link>
              </div>
            </article>

        <!--</div>-->
        <!--</blockquote>-->

	</div>
</template>

<script>
import ReviewsComponent from '../components/ReviewsComponent.vue';

export default {
    props: ['game'],
    components: {ReviewsComponent},
    computed: {
        topRecommendations() {
            var grouped = _.groupBy(this.game.recommendations, 'second_game_id');
            return _.sortBy(grouped, length);
            //game.recommendations, consolidate via second_game_id
            //var recomendationsCount= _.countBy(recommendations, 'second_game_id')
        },
        recommendationsCount() {
            return  _.countBy(this.game.recommendations, 'second_game_id')
        },
        recommendationsArray() {
        }
    },
    methods: {
        iconStyle(icon) {
            return {
                backgroundImage: 'url(' + this.$store.state.cdnURL + 'games/icons/' + icon + ')',
                backgroundSize: 'cover',
                backgroundPosition: 'center top', 
                backgroundRepeat: 'no-repeat',
                width: '100%',
                height: '100%'
            }
        },
        getIcon(icon) {
            return (this.$store.state.cdnURL + 'games/icons/' + icon)
        }
    }
    // filters: {
    //     truncate: function(string, value) {
    //         return string.substring(0, value) + '...';
    //     },
    //     dateFormat(date) {
    //         return moment(date).format("MMMM Do, YYYY");
    //     }
    // }
}
</script>