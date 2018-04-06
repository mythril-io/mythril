<template>
<div>
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

		<div class="column is-12">
			<button type="button" class="button is-primary" @click="checkRelease">Add Release</button>
		</div>

		<div class="column is-12" v-if="releases.length > 0">
			<hr>
			<label class="label">
			  Current Releases
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


	              <th class="has-text-centered">Delete?</th>
	            </tr>
	          </thead>
	          <tbody>
	            <tr v-for="release in releases">
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

	              <td class="has-text-centered"><a class="delete" @click="removeRelease(release)"></a></td>
	            </tr>
	          </tbody>
	        </table>
	        
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
    props:['existingReleases'],
	data() { 
		return {
			alternate_title: '',
			platform: null,
			publisher: null,
			coDeveloper: null,
			date: null,
			region: null,

			releases:[],
			dbReleases: [],

			platforms: [],
			publishers: [],
			developers: [],
			regions: []
		}
	},
	filters: {
	  dateFormat(date) {
	    if(date)
	    {
	      return moment(date).format("MMM Do YYYY");;
	    }
	    else
	    {
	      return "N/A";
	    }
	  }
	},
	created(){
		this.getFormData();
		if(this.existingReleases) {
			this.dbReleases = this.existingReleases;
		}
	},
	methods: {
		checkRelease() {
			if(this.platform && this.publisher && this.region && this.date)
	        {
	          //Check if release information is a duplicate of database entires
	          if(this.dbReleases.length > 0)
	          {
	            for(var j = 0; j < this.dbReleases.length; j++)
	            {
	              if(this.platform.name === this.dbReleases[j].platform.name &&
	                this.publisher.name === this.dbReleases[j].publisher.name &&
	                this.alternate_title === this.dbReleases[j].alternate_title &&
	                this.region.name === this.dbReleases[j].region.name)
	              {
	              	flash('This release already exists in the Database. Please enter different release details.', 'error')
	                return;
	              }
	            }
	          }

	          //Check if release information is a duplicate in form entries
	          if(this.releases.length > 0)
	          {
	            for(var j = 0; j < this.releases.length; j++)
	            {
	              if(this.platform.name === this.releases[j].platform.name &&
	                this.publisher.name === this.releases[j].publisher.name &&
	                this.alternate_title === this.releases[j].alternate_title &&
	                this.region.name === this.releases[j].region.name)
	              {
	              	flash('This release was already entered in the form. Please enter different release details.', 'error')
	                return;
	              }
	            }
	          }
	          this.createRelease()
	        }
	        else { flash('Please fill out required release fields.', 'error'); }
		},
		createRelease() {
			//Create a newRelease object
			var newRelease = {
				alternate_title: this.alternate_title,
				platform: this.platform,
				publisher: this.publisher,
				codeveloper: this.coDeveloper,
				region: this.region,
				date: this.date
			}
			//Add newRelease object to releases array
			this.releases.push(newRelease);

			//Send Releases array to parent
			this.$emit('updateReleases', this.releases)

			//Reset Release form
			this.resetForm();
		},
		resetForm() {
			this.alternate_title = '';
			this.platform = null;
			this.publisher = null;
			this.coDeveloper = null;
			this.region = null;
			this.date = null;
		},
		removeRelease: function (release) {
			var index = this.releases.indexOf(release);
			this.releases.splice(index, 1);
			this.$emit('updateReleases', this.releases)
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