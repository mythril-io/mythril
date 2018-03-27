<template>
<div>
  <h1 class="title">Games</h1>
  <div class="content">
<!--       <blockquote>Developers currently in Database: <strong>{{ developers.length }}</strong></blockquote>
      <div v-if="developers.length > 0"> 
          <table>
            <thead>
              <tr>
                <th>Developer</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="developer in developers">
                <td><span class="tag is-light is-medium">{{ developer.name }}</span> ({{ developer.country }})</td>
                <td>{{ developer.description }}</td>
              </tr>
            </tbody>
          </table>
        <bulma-paginate :pages="pages" @paginate="changePage"></bulma-paginate>
      </div>
      <div v-else>
          No games in the database.
      </div> -->

  </div>
  <hr>
  <form>
    <article class="message">
      <div class="message-header"><p>Add a Game</p></div>
      <div class="message-body">

        <div class="columns is-multiline">
          <div class="column is-two-thirds">

                <div class="field">
                  <label class="label">Title</label>
                  <p class="control has-icons-left">
                    <input :class="{'input': true, 'is-danger': errors.has('title') }" type="text" placeholder="Title"  v-model="title" name="title" v-validate="'required'">
                    <span class="icon is-small is-left">
                      <i class="fas fa-folder"></i>
                    </span>
                    <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                  </p>
                </div>

                <div class="field">
                  <label class="label">Developer</label>
                  <p class="control">
                    <multiselect
                      v-model="developer"
                      :options="allDevelopers"
                      :close-on-select="true"
                      placeholder="Select Developer"
                      track-by="name"
                      label="name"
                      :class="{'select': true, 'is-danger': errors.has('developer') }" name="developer" v-validate="'required'">
                    </multiselect>
                    <span v-show="errors.has('developer')" class="help is-danger">{{ errors.first('developer') }}</span>
                  </p>
                </div>

                <div class="field">
                  <label class="label">Genres</label>
                  <p class="control">
                    <multiselect
                      v-model="genres"
                      :options="allGenres"
                      :multiple="true"
                      placeholder="Select Genre"
                      track-by="name"
                      label="name"
                      :class="{'select': true, 'is-danger': errors.has('genre') }" name="genre" v-validate="'required'">
                    </multiselect>
                    <span v-show="errors.has('genre')" class="help is-danger">{{ errors.first('genre') }}</span>
                  </p>
                </div>

                <div class="field">
                  <label class="label">Synopsis</label>
                  <p class="control">
                    <textarea :class="{'textarea': true, 'is-danger': errors.has('synopsis') }" type="text" placeholder="Title"  v-model="synopsis" name="synopsis" v-validate="'required|min:10'"></textarea>
                    <span v-show="errors.has('synopsis')" class="help is-danger">{{ errors.first('synopsis') }}</span>
                  </p>
                </div>
          </div>

          <div class="column is-one-third">
            <div class="field">
              <label class="label">Icon</label>
              <image-upload @updateImage="updateIcon" name="icon"></image-upload>
              <span v-show="errors.has('icon')" class="help is-danger">{{ errors.first('icon') }}</span>
            </div>

            <div class="field">
              <label class="label">Banner</label>
              <image-upload @updateImage="updateBanner" name="banner"></image-upload>
              <span v-show="errors.has('banner')" class="help is-danger">{{ errors.first('banner') }}</span>
            </div>
          </div>
        </div>

        <div class="columns is-multiline">
          <div class="column is-12">
            <hr>
            <label class="label">Release(s)</label>
            <release-form @updateReleases="updateReleases"></release-form>
          </div>

          <div class="column is-half is-offset-one-quarter">
            <div class="field">
              <p class="control">
                <button class="button is-primary is-fullwidth" @click.prevent="validateBeforeSubmit" :disabled="errors.any()">Add Game</button>
              </p>
            </div>
          </div>

        </div>

      </div>
    </article>
  </form>
          
</div>
</template>

<script>
import NProgress from 'nprogress'
import ErrorHandler from '../../../../utilities/ErrorHandler'

import Multiselect from 'vue-multiselect'
import ReleaseForm from './ReleaseForm.vue'
import ImageUpload from '../../../utilities/ImageUpload.vue'


export default {
  components: {Multiselect, ReleaseForm, ImageUpload},
  data() {
    return {
        title: '',
        developer: null,
        genres: null, 
        synopsis: '', 
        icon: '',
        banner: '', 
        releases: [],

      allDevelopers: [],
      allGenres: [],
      games: []
    }
  },
  created(){
    this.getFormData();
  },
  methods: {
      validateBeforeSubmit() {
        this.$validator.validateAll().then((result) => {
          if(result) {
            if(this.releases.length == 0) { flash('Please enter at least 1 release.', 'error') }
            else { this.addGame() }
          } else { flash('Please fill out required fields.', 'error') }
        });
      },
      updateIcon(file) {
        this.icon = file;
      },
      updateBanner(file) {
        this.banner = file;
      },
      updateReleases(releases) {
        this.releases = releases
      },

      addGame() {
        var success = true;
        NProgress.start();
        axios.post('/api/admin/games', {title: this.title, developer: this.developer, genres: this.genres, synopsis: this.synopsis, icon: this.icon, banner: this.banner, releases: this.releases})
        .catch((error) => { 
          var nameError = error.response.data.errors.title
          if(nameError)
          {
            NProgress.done();
            success = false;
            var errorsReadable = ErrorHandler.array(nameError);
            flash(errorsReadable, 'error')
          }
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              this.games.push(response.data)
              flash('Game Added', 'success')
            }
        });
      },

      getFormData() {
        axios.get('/api/developers')
        .then((response) => { this.allDevelopers = response.data; })
        .catch((error) => console.log("Developers array not updated."));

        axios.get('/api/genres')
        .then((response) => { this.allGenres = response.data; })
        .catch((error) => console.log("Genres array not updated."));
      }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
