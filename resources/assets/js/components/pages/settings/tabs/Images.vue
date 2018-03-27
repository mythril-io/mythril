<template>
<div>
    <div class="content">
        <h1>Avatar</h1>
    </div>
    	<div class="columns">
    		<div class="column is-narrow" v-if="this.user.avatar">
    			
		            <div class="card">
		                <div class="card-image imageFade">
		                    <figure class="image">
		                        <img :src="existingAvatar" class="imageFade">
		                    </figure>
		                </div>
		            </div><br>
		            <button class="button is-danger" @click="updateImages('deleteAvatar')">Remove Current Avatar</button>
    		</div>
    		<div class="is-divider-vertical" data-content="OR" v-if="this.user.avatar"></div>
    		<div class="column">
		    	<image-upload @updateImage="updateAvatar" width="300" name="avatar" ref="avatarComponent" validation="image|size:2000"></image-upload><br>
		    	Images are resized to a width of 300px but retain aspect ratio. Preferably, upload an image with an aspect ratio of 1:1.
		        <span v-show="errors.has('avatar')" class="help is-danger">{{ errors.first('avatar') }}</span>
    		</div>
    	</div>
    
    <hr>
    <div class="content">
        <h1>Profile Banner</h1>
    </div>
    	<div class="columns">
    		<div class="column is-narrow" v-if="this.user.banner">
	            <div class="card" style="max-width: 600px !important">
	                <div class="card-image imageFade">
	                    <figure class="image">
	                        <img :src="existingBanner" class="imageFade">
	                    </figure>
	                </div>
	            </div><br>
		        <button class="button is-danger" @click="updateImages('deleteBanner')">Remove Current Banner</button>
    		</div>
    		<div class="is-divider-vertical" data-content="OR" v-if="this.user.banner"></div>
    		<div class="column">
		    	<image-upload @updateImage="updateBanner" width="600" name="banner" ref="bannerComponent" validation="image|size:6000"></image-upload>
		        <span v-show="errors.has('banner')" class="help is-danger">{{ errors.first('banner') }}</span>
    		</div>
    	</div>

	<hr>
	<div class="notification"><strong>Note:</strong> You may have to refresh the page to see your updated avatar or banner, depending on your browser cache settings.</div>

	<div class="field">
		<div class="control">
			<button class="button is-primary" @click="validateBeforeSubmit">Update</button>
		</div>
	</div>

</div>
</template>

<script>
import store from '../../../../store.js'
import ImageUpload from '../../../utilities/ImageUpload.vue'

export default {
    props: ['user'],
  	components: {ImageUpload},
    data() {
      return {
        avatar: null,
        banner: null,

		existingAvatar: '',
		existingBanner: ''
      }
    },
    watch: {
      user: function () { 
		this.updateExistingImages()
      }
    },
    methods: {
      	validateBeforeSubmit() {
	        this.$validator.validateAll().then((result) => {
	          if (result) { this.updateImages('images') }
	        });
      	},
        updateExistingImages() {
			this.existingAvatar = 'https://mythril.nyc3.digitaloceanspaces.com/users/avatars/' + this.user.avatar;
			this.existingBanner = 'https://mythril.nyc3.digitaloceanspaces.com/users/banners/' + this.user.banner;
        },
		updateAvatar(file) {
			this.avatar = file;
		},
		updateBanner(file) {
			this.banner = file;
		},
  	  	updateImages(action) {
			axios.patch(('/api/users/' + this.user.id ), {
              avatar: this.avatar,
              banner: this.banner,
              update: action
            })
		    .then((response) => { 
		    	if((response.status === 401) || (response.status === 403) || (response.status === 404)) { 
                this.$router.replace({ name: 'Home'}) 
                flash('Unauthorized to Update Settings', 'error')
              }
              else if(response.status === 200) { 
                flash('Settings Updated', 'success')
                this.$store.commit('updateUser', response.data)
                this.$refs.avatarComponent.removeImage()
                this.$refs.bannerComponent.removeImage()
                this.updateExistingImages()
			  	document.body.scrollTop = 0; // For Safari
			  	document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
              }
		    })
		    .catch((error) => this.$router.replace({ name: 'Home'}) );
  	  	}
  	},
    created() {
      this.updateExistingImages()
    }
}
</script>