<template>

    <div class="columns is-centered">
      <div class="column is-narrow recomendation-col is-clearfix">
        <div class="image-behind is-pulled-left" >
          <b-tooltip :label="recommendation.release.alternate_title ? recommendation.release.alternate_title : recommendation.game.title">
            <div class="card image imageFade text-container">
                <router-link :to="{name: 'Game', params: { id: recommendation.game.id }}">
                    <img v-lazy="this.$store.state.cdnURL + 'games/icons/' + recommendation.game.icon" :alt="recommendation.release.alternate_title ? recommendation.release.alternate_title : recommendation.game.title" class="image is-128x128">
                </router-link>
            </div>
          </b-tooltip>
        </div>
        <div class="recomendation-image is-pulled-right">
          <b-tooltip position="is-bottom" :label="recommendation.second_release.alternate_title ? recommendation.second_release.alternate_title : recommendation.second_game.title">
            <div class="card image imageFade text-container">
                <router-link :to="{name: 'Game', params: { id: recommendation.second_game.id }}">
                    <img v-lazy="this.$store.state.cdnURL + 'games/icons/' + recommendation.second_game.icon" :alt="recommendation.second_release.alternate_title ? recommendation.second_release.alternate_title : recommendation.second_game.title" class="image is-128x128">
                </router-link>
            </div>
          </b-tooltip>
        </div>
      </div>
      <div class="column">
        <div class="subtitle">"If you liked 
          <router-link :to="{name: 'Game', params: { id: recommendation.game.id }}">
            {{ recommendation.release.alternate_title ? recommendation.release.alternate_title : recommendation.game.title }}</router-link>, you may also like
          <router-link :to="{name: 'Game', params: { id: recommendation.second_game.id }}">
            {{ recommendation.second_release.alternate_title ? recommendation.second_release.alternate_title : recommendation.second_game.title }}</router-link>." - 
            <em>
                <router-link :to="{name: 'User', params: { id: recommendation.user.id }}">
                    {{ recommendation.user.username }}
                </router-link>
            </em>
            <span class="has-text-grey is-size-6">on {{ dateFormat(recommendation.created_at) }}</span>
        </div>
        <div class="block"> 
          <router-link :to="{name: 'Recommendation', params: { id: recommendation.id }}" class="button is-primary">
            <span class="icon is-small">
              <i class="fas fa-file-alt"></i>
            </span>
           <span>Read Recommendation</span>
          </router-link>
        </div>
        
      </div>
    </div>

</template>

<script>
var moment = require('moment-timezone');

export default {
    props: ['recommendation'],
    filters: {
        truncate: function(string, value) {
            return string.substring(0, value) + '...';
        }
    },
    methods: {
        dateFormat(date) {
            if(this.user) {
                if(this.user.timezone) {
                    return moment.utc(date).tz(this.user.timezone).format('MMM Do, YYYY');
                }               
            }
            return moment.utc(date).local().format('MMM Do, YYYY');
        }
    }
}
</script>