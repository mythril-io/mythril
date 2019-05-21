<template>
	<div>
		<div class="content">
			<h2>Discussions</h2>
		</div>

	    <article class="message" v-if="discussions > 0">
	      <div class="message-body">
	        Currently, there are <strong>{{ discussions.length }}</strong> discussions in the database. <strong><router-link :to="{name: 'CreateDiscussion' }">Start a Discussion!</router-link></strong>
	      </div>
	    </article>

        <div style="position: relative; min-height: 100px;">

            <b-loading :is-full-page="false" :active.sync="isLoadingDiscussions"></b-loading>

            <div v-if="!isLoadingDiscussions && discussions.length>0">
              <div v-for="discussion in discussions" :key="discussion.id">
                  <discussion-item :discussion="discussion"></discussion-item>
              </div>
            </div>

            <div v-if="discussions.length==0">
            <article class="message is-warning">
                <div class="message-body">
                No Discussions Found. Be the first to start one! <router-link :to="{name: 'CreateDiscussion' }">Start a Discussion!</router-link>
                </div>
            </article>
            </div>

            <b-pagination
            v-if="!isLoadingDiscussions && pages>1"
            :total="total"
            :current.sync="current"
            per-page="15"
            @change="changePage">
            </b-pagination>

        </div>

	</div>
</template>

<script>
import DiscussionItem from '../../forums/discussions/components/DiscussionItem.vue'

export default {
  components: {DiscussionItem},
  props: ['game'],
  data() {
    return {
      //Discussions
      discussions: [],
      isLoadingDiscussions: true,

      //Pagination
      current: 1,
      total: 0,
      pages: 0,
    };
  },
  methods: {
    getDiscussions(page = 1) {
      this.isLoadingDiscussions = true;

      var query = "?page=" + page;

      axios.get("/api/games/" + this.game.id + '/forums' + query)
        .then(response => {
          this.discussions = response.data.data;
          this.total = response.data.total;
          this.pages = response.data.last_page;
          this.isLoadingDiscussions = false
        })
        .catch(error => {
          this.discussions = [];
          this.isLoadingDiscussions = false
        });
    },
    changePage(pageNum) {
      this.isLoadingDiscussions = true;
      this.$router.push({ name: "GameForums", params: { id: this.game.id }, query: { page: pageNum } });
    },
  },
  created() {
    if (this.$route.query.page) {
      var page = Number(this.$route.query.page);
    } else {
      var page = 1;
    }
    this.current = page;

    this.getDiscussions(page);
  }
};
</script>