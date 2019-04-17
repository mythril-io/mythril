<template>
  <div>
    <div v-if="reviews.length > 0">
      <div class="columns is-mobile" v-for="review in reviews">
        <div class="column is-2 is-hidden-touch">
					<user-avatar :user="review.user"/>
        </div>
        <div class="column">
          <div class="is-clearfix">
            <div class="is-pulled-left">
              <div class="control">
                <div class="tags has-addons">
                  <router-link
                    class="tag is-dark"
                    :to="{name: 'User', params: { id: review.user.id }}"
                  >
                    <b>{{ review.user.username }}</b>
                  </router-link>
                  <span class="tag">{{ dateFormat(review.created_at) }}</span>
                </div>
              </div>
            </div>
            <div class="is-pulled-right">
              <div class="field is-grouped is-grouped-multiline">
                <div class="control">
                  <div class="tags has-addons">
                    <span class="tag">Score</span>
                    <span class="tag is-primary">
                      <b>{{ review.score }}%</b>
                    </span>
                  </div>
                </div>
                <div class="control">
                  <div class="tags has-addons">
                    <a
                      class="tag"
                      :href="'/games?platforms=[' + review.release.platform.id + ']'"
                      :title="review.release.platform.name"
                    >{{ review.release.platform.acronym }}</a>
                  </div>
                </div>
                <div class="control">
                  <div class="tags has-addons">
                    <span
                      class="tag"
                      v-if="(review.like_count + review.dislike_count) == 0"
                    >0 users liked this review</span>
                    <span
                      class="tag"
                      v-else
                    >{{ review.like_count }} out of {{ review.like_count + review.dislike_count }} users liked this review</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <br>
          <div class="content">
            <blockquote>
              {{ review.summary | truncate('200') }}
              <router-link :to="{ name: 'Review', params: { id: review.id }}">[Read More]</router-link>
            </blockquote>
          </div>
        </div>
      </div>
    </div>

    <article class="message is-warning" v-else-if="game">
      <div class="message-body">No reviews found. Be the first to write one!
        <router-link :to="{name: 'CreateReview', query: { game: game.id }}">Write a Review</router-link>
      </div>
    </article>

    <article class="message is-warning" v-else>
      <div class="message-body">No reviews found.</div>
    </article>
  </div>
</template>

<script>
var moment = require("moment-timezone");
import UserAvatar from '../../components/UserAvatar.vue';

export default {
  props: ["reviews", "game"],
  components: {UserAvatar},
  filters: {
    truncate: function(string, value) {
      if (string.length < value) {
        return string;
      }
      return string.substring(0, value) + "...";
    }
  },
  methods: {
    avatarStyle(avatar) {
      return {
        backgroundImage:
          "url(" + this.$store.state.cdnURL + "users/avatars/" + avatar + ")",
        backgroundSize: "cover",
        backgroundPosition: "center top",
        backgroundRepeat: "no-repeat",
        width: "100%",
        height: "100%"
      };
    },
    dateFormat(date) {
      if (this.user) {
        if (this.user.timezone) {
          return moment
            .utc(date)
            .tz(this.user.timezone)
            .format("MMMM Do, YYYY");
        }
      }
      return moment
        .utc(date)
        .local()
        .format("MMMM Do, YYYY");
    }
  }
};
</script>