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
              <li><a class="notification is-primary">All</a></li>
              <li v-for="tag in parentTags">
                <a>{{ tag.name }}</a>
                <ul v-if="tag.children">
                  <li v-for="tag in tag.children">
                    <a>{{ tag.name }}</a>
                  </li>
                </ul>
              </li>
            </ul>
          </aside>

        </div>
        <div class="column">

            <div class="tabs">
                <ul>
                <li class="is-active">
                    <a>
                        <span class="icon is-small"><i class="far fa-bell" aria-hidden="true"></i></span>
                        <span>RECENT</span>
                    </a>
                </li>
                <li>
                    <a>
                        <span class="icon is-small"><i class="far fa-comments" aria-hidden="true"></i></span>
                        <span>TOP</span>
                    </a>
                </li>
                <li>
                    <a>
                        <span class="icon is-small"><i class="fas fa-envelope-open-text" aria-hidden="true"></i></span>
                        <span>SUBSCRIBED</span>
                    </a>
                </li>
                </ul>
            </div>

            <div style="position: relative; min-height: 100px;">
<!-- <nav class="level is-mobile">
  <div class="level-left">
    <div class="level-item is-hidden-mobile">
      <p class="subtitle is-5">
        <strong>123</strong> discussions
      </p>
    </div>
    <div class="level-item">
      <div class="field has-addons">
        <p class="control">
          <input class="input" type="text" placeholder="Find a post">
        </p>
        <p class="control">
          <button class="button">
            Search
          </button>
        </p>
      </div>
    </div>

  </div>

  <div class="level-right">
      <p class="level-item">
        <a class="button is-light">
          <b-icon
              pack="fas"
              icon="check"
              size="is-small">
          </b-icon>
        </a>
      </p>
      <div class="level-item">
        <b-dropdown position="is-bottom-left" aria-role="list">
            <button class="button is-primary" slot="trigger">
                <span>Filters</span>
                <b-icon icon="menu-down"></b-icon>
            </button>

            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-light">General</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-primary">Games</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-info">News</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-danger">Anime</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-warning">Music</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-success">Movies</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom paddingless>
              <hr class="dropdown-divider">
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-info">Site Updates</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-light">Feedback</b-checkbox>
              </div>
            </b-dropdown-item>
            <b-dropdown-item aria-role="listitem" custom>
              <div class="field">
                  <b-checkbox :value="true" type="is-light">Bugs</b-checkbox>
              </div>
            </b-dropdown-item>
        </b-dropdown>
      </div>
    <p class="level-item"><strong>All</strong></p>
    <p class="level-item"><a>Published</a></p>
    <p class="level-item"><a>Drafts</a></p>
    <p class="level-item"><a>Deleted</a></p>
    <p class="level-item"><a class="button is-success">New</a></p>
  </div>
</nav> -->


              <b-loading :is-full-page="false" :active.sync="isLoadingTags"></b-loading>
            </div>

        </div>
      </div>

    </div>
  </section>

</div>
</template>
<script>
import ForumHeader from './common/ForumHeader.vue'

export default {
  components: {ForumHeader},
  data() {
    return {
      tags: [],
      isLoadingTags: true,
      hideTags: true,
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

  },
  created() {
    axios
      .get("/api/forums/tags")
      .then(response => {
        this.tags = response.data;
                setTimeout(() => {
                  this.isLoadingTags = false
                }, 3 * 1000)
        // this.isLoadingTags = false;
      })
      .catch(error => console.log("Tags array not updated."));
  }
};
</script>
