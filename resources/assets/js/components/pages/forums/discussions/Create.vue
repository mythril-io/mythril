<template>
<div>

  <forum-header></forum-header>

	<section class="section">
		<div class="container slide-in-bottom">
            
            <h1 class="title">Start a Discussion</h1>

            <div class="field">
                <label class="label">Title</label>
                <div class="control has-icons-left is-expanded">
                    <span class="icon is-small is-left">
                        <i class="fas fa-comment-alt"></i>
                    </span>
                    <input class="input" type="text" v-model="title" name="title" placeholder="" 
                        v-validate="'required|min: 10'">
                </div>
                <p v-show="errors.has('title')" class="help is-danger">
                    {{ errors.first('title') }}
                </p>
            </div>

            <div class="field">
                <b-field label="Tag (Select at least one)">
                    <b-taginput
                        v-model="selectedTags"
                        :data="filteredTags"
                        autocomplete
                        :open-on-focus="true"
                        :allow-new="false"
                        :clear-on-select="true"
                        field="name"
                        maxtags="3"
                        pack="mdi"
                        icon="label"
                        type="is-info"
                        :placeholder="tagsList"
                        @typing="getFilteredTags">
                    </b-taginput>
                </b-field>
            </div>
            

            <!-- <transition name="slide-down">
            <div class="field" v-if="selectedTags.length == 1 && tagsHasGame">
                <b-field label="Child Tag (optional)">
                    <b-taginput
                        v-model="selectedChildTags"
                        :data="filteredChildTags"
                        autocomplete
                        :allow-new="false"
                        field="name"
                        maxtags="2"
                        icon="label"
                        type="is-warning"
                        placeholder="e.g. Release Discussions"
                        @typing="getFilteredChildTags">
                    </b-taginput>
                </b-field>
            </div>
            </transition> -->

            <transition name="slide-down">
            <div class="field" v-if="selectedTags && selectedTags.length && !stopTagSelection">
                <b-field label="Games (optional)">
                    <b-taginput
                        v-model="selectedGames"
                        :data="games"
                        autocomplete
                        :allow-new="false"
                        field="title"
                        maxtags="2"
                        :loading="isFetching"
                        icon="label"
                        type="is-primary"
                        :allow-duplicates="false"
                        placeholder="e.g. Final Fantasy VII"
                        @typing="getAsyncGames">
                        <template slot-scope="props">
                            <div class="media">
                                <div class="media-left">
                                        <img width="37" :src="`${cdnURL}games/icons/${props.option.icon}`">
                                </div>
                                <div class="media-content">
                                    <strong>{{ props.option.title }}</strong>
                                    <br>
                                    <small>Popularity: #{{ props.option.popularity_rank }} |
                                        Score: {{ props.option.score ? (props.option.score | percentageFormat) + '%' : 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </template>
                    </b-taginput>
                </b-field>
            </div>
            </transition>

            <br>
            <div class="field">
                <markdown-editor 
                    v-model="body" 
                    name="body"
                    v-validate="'required|min: 10'">
                </markdown-editor>
                <p v-show="errors.has('body')" class="help is-danger">{{ errors.first('body') }}</p>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-primary" @click="validateBeforeSubmit" :disabled="errors.any()">Post Discussion</button>
                </div>
            <p class="control">
                <button class="button is-light" @click="confirmCancel">Cancel</button>
            </p>
            </div>

		</div>
	</section>


</div>
</template>

<script>
import ForumHeader from '../common/ForumHeader.vue'
import MarkdownEditor from '../../../utilities/MarkdownEditor.vue'
import VeeValidate from 'vee-validate';
import NProgress from 'nprogress'
import { ModalProgrammatic } from 'buefy/dist/components/modal'
import AuthenticationModal from "../../../utilities/AuthenticationModal.vue";

export default {
  components: {ForumHeader, MarkdownEditor},
  data() {
    return {
      selectedTags: [],
      filteredTags: [],
      tags: [],

      title: '',
      body: '',

    //   selectedChildTags: [],
    //   filteredChildTags: [],

      selectedGames: [],
      games: [],
      isFetching: false 
    };
  },
  watch: {
    selectedTags: function () {
      this.getFilteredTags()
      if(this.selectedTags.length == 0) {
          this.clearChildGameTags()
      }
    },
    // selectedChildTags: function () {
    //   this.getFilteredChildTags()
    // }
  },
  computed: {
    cdnURL() {
      return this.$store.state.cdnURL;
    },
    tagsList() {
        var list = [];
        _.map(this.tags, function(tag){ 
            if (tag.parent_id == null && tag.slug != 'site-updates') {
                list.push(tag.name)
            }
        });

        // list
        list = list.toString().split(',').join(', ');
        return 'e.g. ' + list;
    },
    tagsHasGame() {
        var boolean = false;
            _.map(this.selectedTags, function(tag){ 
                if (tag.slug == 'games') {
                    boolean = true;
                }
            });

        return boolean;
    },
    stopTagSelection() {
        var boolean = false;
            _.map(this.selectedTags, function(tag){ 
                if (tag.slug == 'general' || tag.slug == 'site-updates' || tag.slug == 'site-feedback' || tag.slug == 'bugs') {
                    boolean = true;
                }
            });

        return boolean;
    }
  },
  methods: {
    confirmCancel() {
        this.$dialog.confirm({
            message: 'Are you sure you want to cancel?',
            onConfirm: () => this.$router.push({ name: "Forums" })
        })
    },
    validateBeforeSubmit() {
        this.$validator.validateAll().then((result) => {
            if (result) { 
                var discussion = {
                    'title': this.title,
                    'body': this.body,
                    'selectedTags': this.selectedTags,
                    // 'selectedChildTags': this.selectedChildTags,
                    'selectedGames': this.selectedGames
                }
                this.submitDiscussion(discussion);
            }
        });
    },
    submitDiscussion(discussion) {
        var success = true;
        NProgress.start();
        axios.post('/api/forums/discussions', {
            title: discussion.title,
            body: discussion.body,
            selectedTags: discussion.selectedTags,
            // selectedChildTags: discussion.selectedChildTags,
            selectedGames: discussion.selectedGames
        })
        .catch((error) => { 
            if(error.response.status === 403) { flash(error.response.data.error, 'error') }
            else { 
                this.$snackbar.open({
                    message: 'Discussion Not Posted', 
                    type: 'is-danger',
                })
            }
            NProgress.done();
            success = false;
        })
        .then((response) => {
            if(success) {
                NProgress.done();
                this.$snackbar.open({
                    message: 'Discussion Posted', 
                    type: 'is-primary',
                })
                const id = response.data.id
                this.$router.push({ name: 'Discussion', params: { id }})
            }
        });
    },
    clearChildGameTags() {
        // this.selectedChildTags = [];
        this.selectedGames = [];
    },
    getFilteredTags(text='') {
        //if tag is general, updates/bugs then show no more tags
        if(this.selectedTags && this.selectedTags.length && this.stopTagSelection) {
            this.filteredTags = [];
        }
        //else if tag is NOT general, updates/bugs then show all tags except general, updates/bugs
        else if(this.selectedTags && this.selectedTags.length && !this.stopTagSelection) {
            var tempTags = this.tags.filter((option) => {
                return option.name
                    .toString()
                    .toLowerCase()
                    .indexOf(text.toLowerCase()) >= 0
            })

            this.filteredTags = _.pullAllBy(tempTags, [
                { 'slug': 'general' }, 
                { 'slug': 'site-updates' }, 
                { 'slug': 'site-feedback' }, 
                { 'slug': 'bugs' }
            ], 'slug');
        }
        //else show all tags
        else {
            this.filteredTags = this.tags.filter((option) => {
                return option.name
                    .toString()
                    .toLowerCase()
                    .indexOf(text.toLowerCase()) >= 0
            })
        }
    },
    // getFilteredChildTags(text='') {
    //     var gameTag = _.filter(this.tags, function(o) { return o.id == 2; });
    //     var childTags = JSON.parse(JSON.stringify(gameTag[0].children));
        
    //     var difference = _.differenceWith(childTags, this.selectedChildTags, _.isEqual);
        
    //     this.filteredChildTags = difference.filter((option) => {
    //         return option.name
    //             .toString()
    //             .toLowerCase()
    //             .indexOf(text.toLowerCase()) >= 0
    //     })

    // },
    getAsyncGames: _.debounce(function (name) {
                if (!name.length) {
                    this.games = []
                    return
                }
                this.isFetching = true
                axios.get(`/api/games?search=${name}`)
                .then(response => {
                    this.games = []
                    this.games = _.differenceWith(response.data.data, this.selectedGames, _.isEqual);
                    this.isFetching = false
                })
                .catch(error => console.log("Games array not updated."));
            }, 500)
  },
  created() {
    axios
      .get("/api/forums/tags/all")
      .then(response => {
        var parentTags = _.filter(response.data, function(tag){ return (tag.parent_id == null && tag.id != 7); });
        // var childTags = _.filter(response.data, function(tag){ return tag.parent_id != null; });

        // _.map(parentTags, function(tag){ 
        //     //for each category in childCategories, if the parent_id == tag.id, append it to tag.children
        //     var children = _.filter(childTags, function(childTag){ return childTag.parent_id == tag.id; });
        //     if (children.length > 0) {
        //     tag.children = _.orderBy(children, 'name', 'asc');;
        //     }
        // });

        this.tags = _.orderBy(parentTags, 'order', 'asc');
        this.filteredTags = _.orderBy(parentTags, 'order', 'asc');
      })
      .catch(error => console.log("Tags array not updated."));
  }
}
</script>