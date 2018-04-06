<template>
<div>

	<h1 class="title">Edit/Delete Releases</h1>
    <article class="message">
      <div class="message-header"><p>Step 1: Select a Game</p></div>
      <div class="message-body">

      	<div class="field">
		  <p class="control">
		    <multiselect
		      v-model="selectedGame"
		      :options="games"
		      :close-on-select="true"
		      placeholder="Select a Game"
		      track-by="title"
		      label="title"
		      class="select" name="selectedGame">
		    </multiselect>
		  </p>
		</div>

		<div v-if="selectedGame">
			<label class="label">
			  Releases Currently in the Database
			</label>
	        <table class="table is-fullwidth" style="font-size: 12px;">
	          <thead>
	            <tr>
	              <th>Alt. Title</th>
	              <th>Platform</th>
	              <th>Publisher</th>
	              <th>Co-Developer</th>
	              <th>Region</th>
	              <th>Date</th>

	              <th>NA</th>
	              <th>JP</th>
	              <th>EU</th>
				  <th>WW</th>

	              <th></th>
	              <th></th>
	            </tr>
	          </thead>
	          <tbody>
	            <tr v-for="release in selectedGame.releases">
	              <td>{{ release['alternate_title'] ? release['alternate_title'] : "N/A" }}</td>
	              <td>{{ release['platform']['name'] }}</td>
	              <td>{{ release['publisher']['name'] }}</td>
	              <td>{{ release['codeveloper'] ? release['codeveloper']['name'] : "N/A"  }}</td>
	              <td>{{ release['region'] ? release['region']['name'] : "-" }}</td>
	              <td>{{ release['date'] | dateFormat }}</td>

			              <td>{{ release['NA'] | dateFormat }}</td>
			              <td>{{ release['JP'] | dateFormat }}</td>
			              <td>{{ release['EU'] | dateFormat }}</td>
						  <td>{{ release['WW'] | dateFormat }}</td>

	              <td class="has-text-centered"><a class="button is-primary" @click="toggleEdit(release)">Edit</a></td>
	              <td class="has-text-centered"><a class="button is-danger" @click="toggleDelete(release)">Delete</a></td>
	            </tr>
	          </tbody>
	        </table>
		</div>

      </div>
  	</article>

	<delete-form 
	  v-if="deleteRelease" 
	  :resource="'release (Publisher: ' + deleteRelease.publisher.name +', Platform: ' + deleteRelease.platform.name + ')'" 
	  @confirm="deleteReleasePost"
	  @close="deleteRelease = null">
	</delete-form>

    <article class="message" v-if="editRelease">
      <div class="message-header"><p>Step 2: Edit Release</p></div>
      <div class="message-body">

        <release-edit-form :release="editRelease" @editConfirm="checkRelease"></release-edit-form>

      </div>
  	</article>

</div>
</template>

<script>
import NProgress from 'nprogress'
import Multiselect from 'vue-multiselect'
import DeleteForm from '../../../utilities/DeleteForm.vue'
import ReleaseEditForm from './ReleaseEditForm.vue'
import moment from 'moment';

export default {
	components: {Multiselect, DeleteForm, ReleaseEditForm},
	data() {
    	return {
    		selectedGame: null,
    		games: [],

    		deleteRelease: null,
    		editRelease: null,
    	}
	},
	filters: {
	  dateFormat(date) {
	    if(date)
	    {
	      return moment.utc(date).format("MMM Do YYYY");;
	    }
	    else
	    {
	      return "N/A";
	    }
	  }
	},
	created() {
		axios.get('/api/admin/games')
        .then((response) => { this.games = response.data; })
        .catch((error) => console.log("Games array not updated."));
	},
	methods: {
      	toggleDelete(release) {
      		this.deleteRelease = release;
      		this.editRelease = null;
      	},
      	toggleEdit(release) {
      		this.editRelease = release;
      		this.deleteRelease = null;
      	},
      	deleteReleasePost() {
	        var success = true;
	        NProgress.start();
	        axios.delete(('/api/admin/releases/' + this.deleteRelease.id + '/delete'))
	        .catch((error) => { 
	            NProgress.done();
	            success = false;
	            flash('Unable to Delete Release.', 'error')
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Release Deleted Successfully.', 'success')
	              this.deleteRelease = null;
	            }
	        });
      	},
		checkRelease(editedRelease) {
	          //Check if release information is a duplicate of database entries
	          if(this.selectedGame.releases.length > 0)
	          {
	            for(var j = 0; j < this.selectedGame.releases.length; j++)
	            {
	              if(editedRelease.platform.name === this.selectedGame.releases[j].platform.name &&
	                editedRelease.publisher.name === this.selectedGame.releases[j].publisher.name &&
	                editedRelease.alternate_title === this.selectedGame.releases[j].alternate_title &&
	                editedRelease.region.name === this.selectedGame.releases[j].region.name &&
	                editedRelease.id != this.selectedGame.releases[j].id)
	              {
	              	flash('This release already exists in the Database. Please enter different release details.', 'error')
	                return;
	              }
	            }
	          }
	          this.editReleaseSend(editedRelease)
		},
      	editReleaseSend(editedRelease) {
	        var success = true;
	        NProgress.start();
	        axios.put(('/api/admin/releases/' + editedRelease.id + '/edit'), {release: editedRelease})
	        .catch((error) => { 
	            NProgress.done();
	            success = false;
	            flash('Unable to Edit Release.', 'error')
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Release Edited Successfully.', 'success')
	              this.editRelease = null;
	            }
	        });
      	}
	}
}	
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

