<template>
	
    <router-link :to="{ name: 'Review', params: { id: review.id }}">
        <div class="notification is-paddingless" v-bind:style="bannerStyle(review.game.banner)">
            <div style="color: #FFF; width: 100%; padding: 0px;">
                <div style="padding: 20px 24px 0px 24px;">
                    <strong class="has-text-primary">{{ review.release.alternate_title ? review.release.alternate_title : review.game.title }}</strong><br>
                    <span class="is-hidden-mobile">{{ review.summary | truncate('120') }}</span>
                    <span class="is-hidden-tablet ">{{ review.summary | truncate('60') }}</span>
                </div>
              <hr>
              <div class="is-clearfix" style="padding: 0px 24px 20px 24px;">
                <div class="is-pulled-left">
                    <div class="field is-grouped is-grouped-multiline">
                      <div class="control">
                        <div class="tags has-addons">
                          <span class="tag">Score</span>
                          <span class="tag is-primary"><b>{{ review.score }}%</b></span>
                        </div>
                      </div>
                      <div class="control">
                        <div class="tags has-addons">
                          <span class="tag">{{ review.release.platform.acronym }}</span>
                        </div>
                      </div>
                      <div class="control is-hidden-mobile">
                        <div class="tags has-addons">
                          <span class="tag" v-if="(review.like_count + review.dislike_count) == 0">0 users liked this review</span>
                          <span class="tag" v-else>{{ review.like_count }} out of {{ review.like_count + review.dislike_count }} users liked this review</span>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="is-pulled-right">
                  <div class="control">
                    <div class="tags has-addons">
                        <span class="tag">{{ dateFormat(review.created_at) }}</span>
                        <router-link class="tag is-dark" :to="{name: 'User', params: { id: review.user.id }}" style="cursor: help; text-decoration: none" v-if="!hideUser">
                            {{ review.user.username }}
                        </router-link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </router-link>

</template>

<script>
var moment = require('moment-timezone');

export default {
    props: ['review', 'hideUser'],
    filters: {
        truncate: function(string, value) {
            if(string.length < value) { return string }
            return string.substring(0, value) + '...';
        }
    },
    methods: {
        bannerStyle(banner) {
            return {
                backgroundImage: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(' + this.$store.state.cdnURL + 'games/banners/'+ banner +')',
                backgroundSize: 'cover',
                backgroundPosition: 'center', 
                backgroundRepeat: 'no-repeat',
                height: '200px',
                display: 'flex',
                'align-items': 'flex-end'
            }
        },
        dateFormat(date) {
            if(this.user) {
                if(this.user.timezone) {
                    return moment.utc(date).tz(this.user.timezone).format('DD/MM/YY');
                }               
            }
            return moment.utc(date).local().format('DD/MM/YY');
        }
    }
}
</script>