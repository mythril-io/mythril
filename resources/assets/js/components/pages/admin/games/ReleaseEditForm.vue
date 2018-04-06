<template>
	<div class="columns is-multiline">
		
		<div class="column is-12">
			<div class="content">
				A release must have at least the following:
				<ul>
				  <li>Platform</li>
				  <li>Publisher</li>
				  <li>Release Date from at least one region</li>
				</ul>
			</div>
		</div>

		<div class="column is-12">
			<div class="field">
              <label class="label">Alternate Title (Optional)</label>
              <p class="control has-icons-left">
                <input class="input" type="text" placeholder="Alternate Title" v-model="alternate_title" name="alternate_title" >
                <span class="icon is-small is-left">
                  <i class="fas fa-folder"></i>
                </span>
              </p>
            </div>
		</div>

		<div class="column is-one-third">
			<div class="field">
              <label class="label">Platform</label>
              <p class="control">
                <multiselect
                  v-model="platform"
                  :options="platforms"
                  placeholder="Select Platform"
                  track-by="name"
                  label="name"
                  :close-on-select="true"
                  style="z-index:1000;">
                </multiselect>
              </p>
            </div>
		</div>

		<div class="column is-one-third">
			<div class="field">
              <label class="label">Publisher</label>
              <p class="control">
                <multiselect
                  v-model="publisher"
                  :options="publishers"
                  placeholder="Select Publisher"
                  track-by="name"
                  label="name"
                  :close-on-select="true"
                  style="z-index:1000;">
                </multiselect>
              </p>
            </div>
		</div>

		<div class="column is-one-third">
			<div class="field">
              <label class="label">Co-Developer (Optional)</label>
              <p class="control">
                <multiselect
                  v-model="coDeveloper"
                  :options="developers"
                  placeholder="Select Platform"
                  track-by="name"
                  label="name"
                  :close-on-select="true"
                  style="z-index:1000;">
                </multiselect>
              </p>
            </div>
		</div>

		<div class="column is-half">
			<div class="field">
              <label class="label">Region</label>
              <p class="control">
                <multiselect
                  v-model="region"
                  :options="regions"
                  placeholder="Select Region"
                  track-by="name"
                  label="name"
                  :close-on-select="true"
                  style="z-index:1000;">
                </multiselect>
              </p>
            </div>
		</div>

		<div class="column is-half">
			<label class="label">
			  Date
			  <a class="delete" v-if="date" @click="date = null"></a>
			</label>
			<p class="control has-icons-left">
			  <flat-pickr name="date" v-model="date" class="input" placeholder="Select Date"></flat-pickr>
			  <span class="icon is-small is-left">
			    <i class="fa fa-calendar"></i>
			  </span>
			</p>
		</div>

		<div class="column is-half is-offset-one-quarter">
			<div class="field">
			  <p class="control">
			    <button class="button is-primary is-fullwidth" @click.prevent="prepareRelease">Update Release</button>
			  </p>
			</div>
		</div>

	</div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import moment from 'moment';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
  	components: {Multiselect, flatPickr},
    props:['release'],
	data() { 
		return {
			alternate_title: '',
			platform: null,
			publisher: null,
			coDeveloper: null,
			date: null,
			region: null,
			id: 0,

			platforms: [],
			publishers: [],
			developers: [],
			regions: []
		}
	},
	created(){
		this.getFormData();
		this.updateFormData();
	},
	watch: {
	// whenever release changes, this function will run
		release: function () {
			this.updateFormData();
		}
	},
	methods: {
		prepareRelease() {
			//Create a newRelease object
			if(this.platform && this.publisher && this.region && this.date)
	        {
	        	var editedRelease = {
					alternate_title: this.alternate_title,
					platform: this.platform,
					publisher: this.publisher,
					codeveloper: this.coDeveloper,
					region: this.region,
					date: this.date,
					id: this.id
				}
				//Send Releases array to parent
				this.$emit('editConfirm', editedRelease)
	        }
	        else { flash('Please fill out required release fields.', 'error'); }
		},
		updateFormData() {
			this.alternate_title = this.release.alternate_title;
			this.platform = this.release.platform
			this.publisher = this.release.publisher;
			this.coDeveloper = this.release.coDeveloper;
			this.region = this.release.region;
			this.date = this.release.date;
			this.id = this.release.id;
		},
		getFormData() {
			axios.get('/api/developers')
			.then((response) => { this.developers = response.data; })
			.catch((error) => console.log("Developers array not updated."));

			axios.get('/api/platforms')
			.then((response) => { this.platforms = response.data; })
			.catch((error) => console.log("Platforms array not updated."));

			axios.get('/api/publishers')
			.then((response) => { this.publishers = response.data; })
			.catch((error) => console.log("Publishers array not updated."));

			axios.get('/api/regions')
			.then((response) => { this.regions = response.data; })
			.catch((error) => console.log("Regions array not updated."));
		}
	}
}
</script>