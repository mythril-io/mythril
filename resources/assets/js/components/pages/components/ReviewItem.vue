<template>
	
    <router-link :to="{ name: 'Review', params: { id: review.id }}">
      <article 
        class="columns card is-gapless image-hover-scale custom-light-bg is-mobile" 
        @mouseover="isHovered=true" 
        @mouseleave="isHovered=false" 
        style="max-height: 160px;"
      >
        <figure class="column is-narrow">
          <p class="image is-square is-clipped" style="width: 160px;">
            <img :src="$store.state.cdnURL + 'games/icons/' + review.game.icon">
          </p>
        </figure>
        <div class="column" style="position: relative;">
          <div class="content " style="padding: 12px 15px;">
            <p>
              <strong>{{ review.release.alternate_title ? review.release.alternate_title : review.game.title }}</strong>
              <br>
              {{ review.summary | truncate('120') }}
            </p>
          </div>
          <div 
            v-bind:style= "[isHovered ? {'backgroundColor': 'rgba(229, 229, 229,.85)'} : {'backgroundColor': '#e5e5e5'}]"
            style="position: absolute; bottom: 0; right:0; left:0; padding: 12px 15px; border-top: 0px solid #efefef"
          >
            <div class="is-pulled-left">
                  <div class="field is-grouped is-grouped-multiline">
                    <div class="control">
                      <div class="tags has-addons">
                        <span class="tag">
                        <span class="icon has-text-danger">
                        <i class="fas fa-heart"></i>
                      </span>
                        </span>
                        <span class="tag is-primary"><b>{{ review.score }}%</b></span>
                      </div>
                  </div> 
                  <div class="control">
                    <div class="tags has-addons">
                      
                    <b-tooltip :label="review.release.platform.name" type="is-dark">
                        <span class="tag">
                            {{ review.release.platform.acronym }}
                        </span>
                    </b-tooltip>
                                    
                      
                    </div>
                  </div> 
                  <div class="control is-hidden-mobile">
                    <div class="tags has-addons">

                    <b-tooltip :label="reviewLikes" type="is-dark">
                      <span class="tag">
                        <span class="icon has-text-primary">
                          <i class="fas fa-thumbs-up"></i>
                        </span>
                      </span>
                    </b-tooltip>

                    </div>
                  </div>
              </div>	
            </div>
            <div class="is-pulled-right">
              <div class="control">
                <div class="tags has-addons">

                  <b-tooltip :label="detailedDateFormat(review.created_at)" type="is-dark">
                    <span class="tag">
                      {{ dateFormat(review.created_at) }}
                    </span> 
                  </b-tooltip>

                  <router-link v-if="!hideUser" class="tag is-primary" :to="{name: 'User', params: { id: review.user.id }}" style="cursor: help; text-decoration: none">
                    {{ review.user.username }}
                  </router-link>
                </div>
              </div>
            </div>
          </div>

        </div>
      </article>

    </router-link>


</template>

<script>
var moment = require('moment-timezone');

export default {
    props: ['review', 'hideUser'],
    data() {
      return { 
        isHovered: false
      }
    },
    filters: {
        truncate: function(string, value) {
            if(string.length < value) { return string }
            return string.substring(0, value) + '...';
        }
    },
    computed: {
      reviewLikes() {
        var text = '0 users liked this review';
        if((this.review.like_count + this.review.dislike_count) > 0) {
          text = this.review.like_count + ' out of ' + (this.review.like_count + this.review.dislike_count) + ' users liked this review';
        }
        return text;
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
        },
        detailedDateFormat(date) {
            if(this.user) {
                if(this.user.timezone) {
                    return moment.utc(date).tz(this.user.timezone).format('MMMM Do, YYYY');
                }               
            }
            return moment.utc(date).local().format('MMMM Do, YYYY');
        }
    }
}
</script>