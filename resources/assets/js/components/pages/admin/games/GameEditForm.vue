<template>

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
              <label class="label">Existing Icon</label>
              <img :src="existingIcon" class="imageFade" />
            </div>

            <div class="field">
              <label class="label">Existing Banner</label>
              <img :src="existingBanner" class="imageFade" />
            </div>

			<hr>

            <div class="field">
              <label class="label">Icon</label>
              <image-upload @updateImage="updateIcon" name="icon" validation="required|image|size:2000"></image-upload>
              <span v-show="errors.has('icon')" class="help is-danger">{{ errors.first('icon') }}</span>
            </div>

            <div class="field">
              <label class="label">Banner</label>
              <image-upload @updateImage="updateBanner" name="banner" validation="required|image|size:10000"></image-upload>
              <span v-show="errors.has('banner')" class="help is-danger">{{ errors.first('banner') }}</span>
            </div>
          </div>


          <div class="column is-half is-offset-one-quarter">
            <div class="field">
              <p class="control">
                <button class="button is-primary is-fullwidth" @click.prevent="validateBeforeSubmit">Update Game</button>
              </p>
            </div>
          </div>
        </div>

</template>

<script>
import NProgress from 'nprogress'
import ErrorHandler from '../../../../utilities/ErrorHandler'

import Multiselect from 'vue-multiselect'
import ImageUpload from '../../../utilities/ImageUpload.vue'

export default {
  	components: {Multiselect, ImageUpload},
    props:['game'],
	data() { 
		return {
	        title: '',
	        developer: null,
	        genres: null, 
	        synopsis: '', 
	        icon: '',
	        banner: '',
	        releases: [], 
			id: 0,

			existingIcon: '',
			existingBanner: '',

			allDevelopers: [],
			allGenres:[]
		}
	},
	created(){
		this.getFormData();
		this.updateFormData();
	},
	watch: {
		game: function () {
			this.updateFormData();
		}
	},
	methods: {
		validateBeforeSubmit() {
			this.$validator.validateAll(
				{title: this.title, developer: this.developer, genres: this.genres, synopsis: this.synopsis}
				).then((result) => {
				if(result) { this.prepareGame() }
			});
		},
		updateIcon(file) {
			this.icon = file;
		},
		updateBanner(file) {
			this.banner = file;
		},
		prepareGame() {
			//Check if icon/banner were updated
			// if(this.icon == '') { this.icon = this.game.icon }
			// if(this.banner == '') { this.banner = this.game.banner }
			this.editGameSend();
		},
      	editGameSend() {
	        var success = true;
	        NProgress.start();
	        axios.put(('/api/admin/games/' + this.id + '/edit'), {
	        	title: this.title,
				developer: this.developer,
				genres: this.genres,
				synopsis: this.synopsis,
				icon: this.icon,
				banner: this.banner,
				releases: this.releases
	        })
	        .catch((error) => { 
	            NProgress.done();
	            success = false;
	            flash('Unable to Edit Game.', 'error')
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Game Edited Successfully.', 'success')
	              this.editGame = null;
	            }
	        });
      	},
		updateFormData() {
			this.id = this.game.id;
			this.title = this.game.title;
			this.developer = this.game.developer
			this.genres = this.game.genres;
			this.synopsis = this.game.synopsis;
			this.existingIcon = 'https://mythril.nyc3.digitaloceanspaces.com/games/icons/' + this.game.icon;
			this.existingBanner = 'https://mythril.nyc3.digitaloceanspaces.com/games/banners/' + this.game.banner;
			this.releases = this.game.releases;
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