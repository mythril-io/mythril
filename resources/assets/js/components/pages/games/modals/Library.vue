<template>
<div class="modal" v-bind:class="{ 'is-active': modalState }">
  <div class="modal-background" @click="close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Library ({{ game.title }})</p>
      <button class="delete" aria-label="close" @click="close"></button>
    </header>
    <section class="modal-card-body">

	<div v-if="loading">
		<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
		<br>
	</div>	

	<div v-else-if="userLibrary.length > 0">
		<h6 class="title is-6">Releases currently in your library:</h6>

		<table class="table is-fullwidth is-striped is-hoverable" style="font-size: 0.8rem">
		  <thead>
		    <tr>
		      <th><abbr title="Alternate Title shown if applicable">Platform</abbr></th>
		      <th>Own</th>
		      <th>Digital</th>
		      <th><abbr title="Out of 10">Score</abbr></th>
		      <th>Play Status</th>
		      <th>Notes</th>
		      <th></th>
		    </tr>
		  </thead>
		  <tbody>
		    <tr v-for="entry in userLibrary" :key="entry.id">
		      <td>
				<abbr v-if="entry.release.alternate_title" :title="entry.release.alternate_title">
					{{ entry.release.platform.acronym }}
				</abbr>
				<span v-else>{{ entry.release.platform.acronym }}</span>
				<span class="icon has-text-info tooltip" :data-tooltip="'Publisher: ' + entry.release.publisher.name">
					<i class="fas fa-info-circle"></i>
				</span>
	      	  </td>
		      <td>{{ entry.own ? 'Yes' : 'No' }}</td>
		      <td>{{ entry.digital ? 'Yes' : 'No' }}</td>
		      <td>{{ entry.score ? entry.score : 'N/A'}}</td>
		      <td>{{ entry.play_status.name }}</td>
		      <td>
				<abbr v-if="entry.notes" :title="entry.notes">View</abbr>
				<span v-else>N/A</span>
	      	  </td>
		      <td>
				<div class="buttons">
				  <span class="button is-warning is-small" @click="startEditMode(entry)" title="Edit">
					<span class="icon is-small">
					  <i class="fas fa-edit"></i>
					</span>
				  </span>
				  <span class="button is-danger is-small" @click="deleteEntry(entry)" title="Delete">
					<span class="icon is-small">
					  <i class="fas fa-trash-alt"></i>
					</span>
				  </span>
				</div>
		      </td>
		    </tr>
		    </tbody>
		</table>
	</div>

	<div v-else class="notification is-warning">
	  You have no releases for this game in your library.
	</div>

        <article class="message" :class="{ 'is-warning' : editMode }">
            <div class="message-body">


<div class="field is-horizontal">
  <div class="field-label is-small">
    <label class="label">Release</label>
  </div>
  <div class="field-body">
    <div class="field">
      <div class="control">
        <div class="select is-fullwidth is-small">
			<select v-model="selectedReleaseID" class="is-focused" :disabled="editMode">
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
  </div>
</div>

<div class="field is-horizontal">
  <div class="field-label is-small">
    <label class="label">Misc.</label>
  </div>
  <div class="field-body">
    <div class="field is-narrow">
      <div class="control">
		<label class="checkbox">
		  <input type="checkbox" name="own" v-model="own">
		  <span style="font-size:0.75rem">Own it?</span>
		</label>
		<label class="checkbox">
		  <input type="checkbox" name="digital" v-model="digital">
		  <span style="font-size:0.75rem">Digital?</span>
		</label>
      </div>
    </div>
  </div>
</div>

<div class="field is-horizontal">
  <div class="field-label is-small">
    <label class="label">Status/Score</label>
  </div>
  <div class="field-body">
    <div class="field">
      <p class="control is-expanded">
        <div class="select is-small is-fullwidth">
          <select name="status" v-model="playStatus">
			<option v-for="status in playStatuses" :value="status.id">
			  {{ status.name }}
			</option>
          </select>
        </div>
      </p>
    </div>
    <div class="field">
      <p class="control is-expanded">
        <div class="select is-small is-fullwidth">
          <select name="score" v-model="score">
          	<option :value="null">Select a Score</option>
            <option v-for="n in 10" :value="n">
            	{{ n }}
            </option>
          </select>
        </div>
      </p>
    </div>
  </div>
</div>

<div class="field is-horizontal">
  <div class="field-label is-small">
    <label class="label">Time/Notes</label>
  </div>
  <div class="field-body">
    <div class="field">
      <p class="control is-expanded">
        <input class="input is-small" type="number" min="1" name="hours" placeholder="Hours Played" v-model="hours">
      </p>
    </div>
    <div class="field">
      <p class="control is-expanded">
        <input class="input is-small" type="input" name="notes" placeholder="Notes..." v-model="notes">
      </p>
    </div>
  </div>
</div>
            </div>
        </article>
	
    </section>
    <footer class="modal-card-foot">
    	<span v-if="editMode">
    		<button class="button is-primary" @click="validateBeforeSubmit('update')">Update Entry</button>
    		<button class="button is-danger" @click="endEditMode()">Cancel</button>
    	</span>
    	<span v-else>
    		<button class="button is-primary" :disabled="loading" @click="validateBeforeSubmit('add')">Add to Library</button>
    	</span>
    </footer>
  </div>
</div>

</template>

<script>
import NProgress from 'nprogress'

export default {
	props: [ 'modalState', 'game', 'user' ],
	data() {
		return {
			selectedReleaseID: null,
			own: true,
			digital: false,
			playStatus: 1,
			score: null,
			hours: null,
			notes: '',

			loading: true,
			editMode: false,
			editEntry: null,
			playStatuses: [],
			userLibrary: []
		}
	},
 	 watch: {
    	user: function () { this.checkUserLibrary() }
  	},
	methods: {
    	close() {
    		this.endEditMode();
      		this.$emit('close')
    	},
    	startEditMode(entry) {
			this.selectedReleaseID = entry.release_id;
			this.own = entry.own;
			this.digital = entry.digital;
			this.playStatus = entry.play_status_id;
			this.score = entry.score;
			this.hours = entry.hours;
			this.notes = entry.notes;

			this.editEntry = entry;
			this.editMode = true;
    	},
    	endEditMode() {
    		this.clearForm();
			this.editMode = false;
			this.editEntry = null;
    	},
      	validateBeforeSubmit(action) {
      		if(this.selectedReleaseID === null) { flash('Please Select a Release', 'error') }
      		else {
				if (action == 'add') { this.addEntry() }
				else if (action == 'update') { this.updateEntry() }
      		}
      	},
    	addEntry() {
	        var success = true;
	        NProgress.start();
	        axios.post('/api/libraries/', {
	        	release_id: this.selectedReleaseID,
	        	play_status_id: this.playStatus,
	        	own: this.own,
	        	digital: this.digital,
	        	score: this.score,
	        	hours: this.hours,
	        	notes: this.notes,
	        	user_id: this.user.id,
	        	game_id: this.game.id
	        })
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Library Entry Not Added', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              this.userLibrary.push(response.data)
	              this.$emit('update', (this.userLibrary.length > 0 ? true : false));
	              this.clearForm();
	              NProgress.done();
	              flash('Library Entry Added', 'success');
	            }
	        });
    	},
    	updateEntry() {
	        var success = true;
	        NProgress.start();
	        axios.patch(('/api/libraries/' + this.editEntry.id), {
	        	play_status_id: this.playStatus,
	        	own: this.own,
	        	digital: this.digital,
	        	score: this.score,
	        	hours: this.hours,
	        	notes: this.notes
	        })
	        .catch((error) => { 
	        	if((error.response.status === 404) | (error.response.status === 403)) { flash(error.response.data.error, 'error') }
	        	else{ flash('Library Entry Not Updated', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              this.$emit('update', (this.userLibrary.length > 0 ? true : false));
	              //Replace userLibrary with new item
	                var index = this.userLibrary.indexOf(this.editEntry);
					this.userLibrary[index] = response.data;
	              this.endEditMode();
	              flash('Library Entry Updated', 'success');
	            }
	        });
    	},
    	deleteEntry(entry) {
	        var success = true;
	        NProgress.start();
	        //library/games/{gid}/releases/{rid}/user/{uid}/delete
	        axios.delete('/api/libraries/' + entry.id)
	        .catch((error) => { 
	        	if(error.response.status === 403) { flash(error.response.data.error, 'error') }
	        	else{ flash('Unable to Remove Library Entry', 'error') }
	            NProgress.done();
	            success = false;
	        })
	        .then((response) => {
	            if(success) {
	            	NProgress.done();
	            	this.endEditMode();
		    		var index = this.userLibrary.indexOf(entry);
					this.userLibrary.splice(index, 1);
		    		this.$emit('update', (this.userLibrary.length > 0 ? true : false));
		    		flash('Library Entry Removed', 'success')
	            }
	        });
    	},
    	clearForm() {
			this.selectedReleaseID = null;
			this.own = true;
			this.digital = false;
			this.playStatus = 1;
			this.score = null;
			this.hours = null;
			this.notes = '';
    	},
    	checkUserLibrary() {
	        axios.get('/api/libraries/games/' + this.$route.params.id + '/users/' + this.user.id )
	        .then((response) => { 
	        	this.userLibrary = response.data 
	        	this.loading = false;
	        })
	        .catch((error) => this.loading = false );
    	}
	},
	created() {
		if(this.user) { this.checkUserLibrary() }
		axios.get('/api/playstatuses')
	    .then((response) => { 
	    	this.playStatuses = response.data; 
	    })
	    .catch((error) => '' );
	}
}
</script>