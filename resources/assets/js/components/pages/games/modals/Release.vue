<template>
<div class="modal" v-bind:class="{ 'is-active': modalState }">
  <div class="modal-background" @click="close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">{{ item }} ({{ game.title }})</p>
      <button class="delete" aria-label="close" @click="close"></button>
    </header>
    <section class="modal-card-body">
		<div v-if="loading">
			<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
		</div>	
		<div v-else class="field">
		  <div class="control">
				<div class="select is-fullwidth is-small">
				  <select v-model="selectedReleaseID" class="is-focused">
				    <option :value="null" disabled>Select a Release</option>
					<option v-for="release in game.releases" :value="release.id">
					  {{ release['platform']['name'] }}
					  {{ release['alternate_title'] ? (' ('+ release['alternate_title'] + ') ') : "" }}
					  | Publisher: {{ release['publisher']['name'] }}
					  {{ release['NA'] ? "[NA]" : "" }}
					  {{ release['JP'] ? "[JP]" : "" }}
					  {{ release['EU'] ? "[EU]" : "" }}
					  {{ release['WW'] ? "[WW]" : "" }}
					</option>
				  </select>
				</div>
			</div>
			<p class="help">If a release has an alternative title, it will be displayed in the select dropbox.</p>
		</div>
    </section>
    <footer class="modal-card-foot">
    	<span v-if="userHasItem">
			<button class="button is-primary" @click="validateBeforeSubmit('update')">Update</button>
			<button class="button is-danger" @click="remove">Remove {{ item == 'Favourite' ? item : 'from ' + item }}</button>
    	</span>
    	<span v-else>
    		<button class="button is-primary" @click="validateBeforeSubmit('add')" :disabled="loading">Add {{ item == 'Favourite' ? item : 'to ' + item }}</button>
    	</span>
    </footer>
  </div>
</div>

</template>

<script>
import NProgress from 'nprogress'
import Multiselect from 'vue-multiselect'

export default {
	props: [ 'modalState', 'game', 'user', 'item' ],
	components: {Multiselect},
	data() {
		return {
			selectedReleaseID: null,
			existingItemID: null,
			userHasItem: false,

			loading: true,
			itemPlural: null,
			itemMessage: null
		}
	},
 	watch: {
    	user: function () { this.checkUserItem() }
  	},
	methods: {
    	close() {
      		this.$emit('close')
    	},
      	validateBeforeSubmit(action) {
      		if(this.selectedReleaseID === null) { flash('Please Select a Release', 'error') }
      		else {
				if (action == 'add') { this.add() }
				else if (action == 'update') { this.update() }
      		}
      	},
    	add() {
	        var success = true;
	        NProgress.start();
	        axios.post(('/api/' + this.itemPlural), {release_id: this.selectedReleaseID, game_id: this.game.id})
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash(this.itemMessage + ' Not Added', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              this.userHasItem = true;
	              this.existingItemID = response.data.id;
	              this.$emit('update', (this.selectedReleaseID ? true : false));
	              flash(this.itemMessage + ' Added', 'success');
	            }
	        });
    	},
    	update() {
	        var success = true;
	        NProgress.start();
	        axios.patch(('/api/'+ this.itemPlural + '/' + this.existingItemID), {release_id: this.selectedReleaseID})
	        .catch((error) => { 
	        	if(error.response.status === 404) { flash(error.response.data.error, 'error') }
	        	else{ flash( this.itemMessage + ' Not Updated', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              this.$emit('update', (this.selectedReleaseID ? true : false))
	              flash( this.itemMessage + ' Updated', 'success');
	            }
	        });
    	},
    	remove() {
	        var success = true;
	        NProgress.start();
	        axios.delete('/api/' + this.itemPlural + '/' + this.existingItemID)
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Unable to Remove ' + this.itemMessage, 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	            	NProgress.done();
		    		this.selectedReleaseID = null;
		    		this.userHasItem = false;
		    		this.existingItemID = null;
		    		this.$emit('update', (this.selectedReleaseID ? true : false));
		    		flash( this.itemMessage  + ' Removed', 'success')
	            }
	        });
    	},
    	checkUserItem() { 
	        axios.get('/api/'+ this.itemPlural + '/games/' + this.$route.params.id + '/users/'+ this.user.id )
	        .then((response) => { 
	        	this.selectedReleaseID = response.data.release_id;
	        	this.existingItemID = response.data.id;
	        	this.userHasItem = true;
	        	this.loading = false;
	        })
	        .catch((error) => { this.userHasItem = false; this.loading = false; } );
    	}
	},
	created() {
		if(this.item == 'Favourite') { 
			this.itemPlural = 'favourites';
			this.itemMessage = 'Favourite';
		}
		if(this.item == 'Wishlist') { 
			this.itemPlural = 'wishlist'; //Plural wouldn't make sense
			this.itemMessage = 'Wishlist Entry';
		}
		if(this.user) { this.checkUserItem() }
	}
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>