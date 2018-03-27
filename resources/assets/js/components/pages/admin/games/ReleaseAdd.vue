<template>
<div>
	<h1 class="title">Add Releases</h1>
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
					<i>Developer:</i> {{ selectedGame.developer.name }}<br>
					<i>Synopsis:</i> {{ selectedGame.synopsis }}
					<hr>
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
			              <th>NA</th>
			              <th>JP</th>
			              <th>EU</th>
			              <th>WW</th>
			            </tr>
			          </thead>
			          <tbody>
			            <tr v-for="release in selectedGame.releases">
			              <td>{{ release['altTitle'] ? release['altTitle'] : "N/A" }}</td>
			              <td>{{ release['platform']['name'] }}</td>
			              <td>{{ release['publisher']['name'] }}</td>
			              <td>{{ release['codeveloper'] ? release['codeveloper']['name'] : "N/A"  }}</td>
			              <td>{{ release['NA'] | dateFormat }}</td>
			              <td>{{ release['JP'] | dateFormat }}</td>
			              <td>{{ release['EU'] | dateFormat }}</td>
			              <td>{{ release['WW'] | dateFormat }}</td>
			            </tr>
			          </tbody>
			        </table>
		</div>

      </div>
  	</article>

    <article class="message" v-if="selectedGame">
      <div class="message-header"><p>Step 2: Add Release(s)</p></div>
      <div class="message-body">

        <release-form :existing-releases="selectedGame.releases" @updateReleases="updateReleasesArray"></release-form>
          <div class="column is-half is-offset-one-quarter">
            <div class="field">
              <p class="control">
                <button class="button is-primary is-fullwidth" @click.prevent="validateBeforeSubmit" :disabled="errors.any()">Update Releases for {{ selectedGame.title }}</button>
              </p>
            </div>
          </div>

      </div>
  	</article>

</div>
</template>

<script>
import NProgress from 'nprogress'
import Multiselect from 'vue-multiselect'
import ReleaseForm from './ReleaseForm.vue'
import moment from 'moment';

export default {
	components: {Multiselect, ReleaseForm},
	data() {
    	return {
    		selectedGame: null,
    		games: [],

    		updatedReleases: []
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
		updateReleasesArray(releases) {
        	this.updatedReleases = releases
      	},
      	validateBeforeSubmit() {
            if(this.updatedReleases.length == 0) { flash('Please enter at least 1 release.', 'error') }
            else { this.updateReleases() }
      	},
      	updateReleases(){
	        var success = true;
	        NProgress.start();
	        axios.put('/api/admin/games/' + this.selectedGame.id + '/releases/edit', {releases: this.updatedReleases})
	        .catch((error) => { 
	            NProgress.done();
	            success = false;
	            flash('Releases not Updated.', 'error')
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Releases Updated.', 'success')
	            }
	        });
      	}
	}
}	
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>