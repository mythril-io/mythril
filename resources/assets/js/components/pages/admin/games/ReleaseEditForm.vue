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
                <input class="input" type="text" placeholder="Alternate Title" v-model="altTitle" name="altTitle" >
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

		<div class="column is-one-quarter">
			<label class="label">
			  North America
			  <a class="delete" v-if="NA" @click="NA = null"></a>
			</label>
			<p class="control has-icons-left">
			  <flat-pickr name="NA" v-model="NA" class="input" placeholder="Select Date"></flat-pickr>
			  <span class="icon is-small is-left">
			    <i class="fa fa-calendar"></i>
			  </span>
			</p>
		</div>

		<div class="column is-one-quarter">
			<label class="label">
			  Japan
			  <a class="delete" v-if="JP" @click="JP = null"></a>
			</label>
			<p class="control has-icons-left">
			  <flat-pickr name="JP" v-model="JP" class="input" placeholder="Select Date"></flat-pickr>
			  <span class="icon is-small is-left">
			    <i class="fa fa-calendar"></i>
			  </span>
			</p>
		</div>

		<div class="column is-one-quarter">
			<label class="label">
			  Europe
			  <a class="delete" v-if="EU" @click="EU = null"></a>
			</label>
			<p class="control has-icons-left">
			  <flat-pickr name="EU" v-model="EU" class="input" placeholder="Select Date"></flat-pickr>
			  <span class="icon is-small is-left">
			    <i class="fa fa-calendar"></i>
			  </span>
			</p>
		</div>

		<div class="column is-one-quarter">
			<label class="label">
			  Worldwide
			  <a class="delete" v-if="WW" @click="WW = null"></a>
			</label>
			<p class="control has-icons-left">
			  <flat-pickr name="WW" v-model="WW" class="input" placeholder="Select Date"></flat-pickr>
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
			altTitle: '',
			platform: null,
			publisher: null,
			coDeveloper: null,
			NA: null,
			EU: null,
			JP: null,
			WW: null,
			id: 0,

			platforms: [],
			publishers: [],
			developers: []
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
			if(this.platform && this.publisher &&
	          (this.NA||this.EU||this.JP||this.WW))
	        {
	        	var editedRelease = {
					altTitle: this.altTitle,
					platform: this.platform,
					publisher: this.publisher,
					codeveloper: this.coDeveloper,
					NA: this.NA,
					EU: this.EU,
					JP: this.JP,
					WW: this.WW,
					id: this.id
				}
				//Send Releases array to parent
				this.$emit('editConfirm', editedRelease)
	        }
	        else { flash('Please fill out required release fields.', 'error'); }
		},
		updateFormData() {
			this.altTitle = this.release.altTitle;
			this.platform = this.release.platform
			this.publisher = this.release.publisher;
			this.coDeveloper = this.release.coDeveloper;
			this.NA = this.release.NA;
			this.EU = this.release.EU;
			this.JP = this.release.JP;
			this.WW = this.release.WW;
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
		}
	}
}
</script>