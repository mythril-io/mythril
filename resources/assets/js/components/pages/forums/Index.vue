<template>
<div>

  <forum-header></forum-header>

  <section class="section">
    <div class="container">
        
      <div class="columns is-desktop">
        <div class="column is-3-desktop">

          <div class="columns is-mobile is-variable is-1-touch">
            <div class="column" style="padding-bottom: 0px;">
              <router-link :to="{name: 'CreateThread'}" class="button is-primary is-fullwidth">Start a Discussion</router-link>
            </div>
            <div class="column is-narrow is-hidden-desktop" style="padding-bottom: 0px;">
              <div class="control">
                <a class="button is-danger"
                  @click="hideTags=!hideTags"
                  v-bind:class="{ 'is-outlined': hideTags }"
                >
                  <span class="icon is-small">
                    <i class="fas fa-tags"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>
          
          <hr class="is-hidden-touch">

          <aside class="menu" v-bind:class="{ 'is-hidden-touch': hideTags }">
            <p class="menu-label">
              Tags
            </p>
            <ul class="menu-list">
              <li>
                <router-link :to="{name: 'Forums'}" :class="{ 'is-active': this.$route.params.tag==undefined }" exact>All</router-link>
              </li>
              <li v-for="tag in parentTags">
                <router-link :to="{name: 'Forums', params: { tag: tag.slug }}">{{ tag.name }}</router-link>
                <ul v-if="tag.children">
                  <li v-for="tag in tag.children">
                    <router-link :to="{name: 'Forums', params: { tag: tag.slug }}">{{ tag.name }}</router-link>
                  </li>
                </ul>
              </li>
            </ul>
          </aside>

        </div>
        <div class="column">

            <div class="tabs">
              <ul>
                <li :class="{ 'is-active': sort=='recent' }">
                    <a @click="setSort('recent')">
                        <span class="icon is-small"><i class="fa-bell" :class="[ sort=='recent' ? 'fas' : 'far']" aria-hidden="true"></i></span>
                        <span>RECENT</span>
                    </a>
                </li>
                <li :class="{ 'is-active': sort=='popular' }">
                    <a @click="setSort('popular')">
                        <span class="icon is-small"><i class="fa-comments" :class="[ sort=='popular' ? 'fas' : 'far']" aria-hidden="true"></i></span>
                        <span>POPULAR</span>
                    </a>
                </li>
                <li :class="{ 'is-active': sort=='likes' }">
                    <a @click="setSort('likes')">
                        <span class="icon is-small"><i class="fa-heart" :class="[ sort=='likes' ? 'fas' : 'far']" aria-hidden="true"></i></span>
                        <span>LIKES</span>
                    </a>
                </li>
                <li :class="{ 'is-active': sort=='views' }">
                    <a @click="setSort('views')">
                        <span class="icon is-small"><i class="fa-eye" :class="[ sort=='views' ? 'fas' : 'far']" aria-hidden="true"></i></span>
                        <span>VIEWS</span>
                    </a>
                </li>
                <li :class="{ 'is-active': sort=='users' }">
                    <a @click="setSort('users')">
                        <span class="icon is-small"><i class="fa-user" :class="[ sort=='users' ? 'fas' : 'far']" aria-hidden="true"></i></span>
                        <span>USERS</span>
                    </a>
                </li>
              </ul>
            </div>

            <!-- Main container -->
            <nav class="level is-mobile">
              <!-- Left side -->
              <div class="level-left">
                <div class="level-item">
                  <div class="field has-addons">
                    <p class="control">
                      <input 
                        class="input" 
                        type="text" 
                        placeholder="Find a discussion"
                        v-model="search"
                        @keyup.enter="applyFilters">
                    </p>
                    <p class="control">
                      <button class="button" @click="applyFilters">
                        Search
                      </button>
                    </p>
                  </div>
                </div>
              </div>

              <!-- Right side -->
              <div class="level-right">
                <p class="level-item"><a>Subscribed</a></p>
                <p class="level-item">
                  <a class="button is-light" title="Mark All as Read">
                    <b-icon
                        pack="fas"
                        icon="check"
                        size="is-small">
                    </b-icon>
                  </a>
                </p>
              </div>
            </nav>

            <div style="position: relative; min-height: 100px;">

              <b-loading :is-full-page="false" :active.sync="isLoadingDiscussions"></b-loading>

              <div v-if="!isLoadingDiscussions">
                <div v-if="discussions.length>0" v-for="discussion in discussions" >
                  <discussion-item :discussion="discussion"></discussion-item>
                </div>

                <div v-if="discussions.length==0">
                  <article class="message is-warning">
                    <div class="message-body">
                      No Discussions Found :(
                    </div>
                  </article>
                </div>
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
      </div>

    </div>
  </section>

</div>
</template>
<script>
import ForumHeader from './common/ForumHeader.vue'
import DiscussionItem from './discussions/DiscussionItem.vue'

export default {
  components: {ForumHeader, DiscussionItem},
  data() {
    return {
      //Tags
      tags: [],
      isLoadingTags: true,
      hideTags: true,

      //Discussions
      discussions: [],
      isLoadingDiscussions: true,

      //Pagination
      current: 1,
      total: 0,
      pages: 0,

      //Filters
      sort: 'recent',
      search: ''
    };
  },
  computed: {
    parentTags() {
      var parentTags = _.filter(this.tags, function(tag){ return tag.parent_id == null; });
      var childTags = _.filter(this.tags, function(tag){ return tag.parent_id != null; });

      _.map(parentTags, function(tag){ 
        //for each category in childCategories, if the parent_id == tag.id, append it to tag.children
        var children = _.filter(childTags, function(childTag){ return childTag.parent_id == tag.id; });
        if (children.length > 0) {
          tag.children = children;
        }
      });

      return _.orderBy(parentTags, 'order', 'asc');
    }
  },
  methods: {
    getDiscussions(page = 1) {
      var query = "?page=" + page;
      if (location.search) {
        query = location.search + "&page=" + page;
      }

      var tag = (this.$route.params.tag ? '/' + this.$route.params.tag : '')

      axios.get("/api/forums" + tag + query)
        .then(response => {
          this.discussions = response.data.data;
          this.total = response.data.total;
          this.pages = response.data.last_page;
          this.isLoadingDiscussions = false
        })
        .catch(error => this.$router.push({ name: "Forums" }));
    },
    changePage(pageNum) {
      this.isLoadingDiscussions = true;
      this.$router.push({ name: "Forums", query: { page: pageNum } });
    },
    getTags() {
      axios.get("/api/forums/tags/all")
        .then(response => {
          this.tags = response.data;
          this.isLoadingTags = false;
        })
        .catch(error => console.log("Tags array not updated."));
    },
    getAndSetQueryParams(){
      if (this.$route.query.sort) {
        this.sort = this.$route.query.sort;
      }
      if (this.$route.query.search) {
        this.search = this.$route.query.search;
      }
    },
    setSort(sort) {
      this.sort = sort;
      this.applyFilters();
    },
    applyFilters() {
      this.$router.replace({
        query: {
          sort: this.sort,
          search: this.search
        }
      });
    }
  },
  created() {
    this.getAndSetQueryParams();
    this.getTags();

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
