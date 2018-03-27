<template>
<div>
<div class="content">
    <h1>Profile</h1>
</div>

<div class="field">
  <label class="label">About Me</label>
  <div class="control">
    <textarea class="textarea" placeholder="Tell everyone about yourself." v-model="aboutMe" name="about"></textarea>
  </div>
  <p v-show="errors.has('about')" class="help is-danger">{{ errors.first('about') }}</p>
</div>

<div class="field">
  <label class="label">Timezone</label>
  <div class="control">
    <multiselect
      v-model="timezone"
      :options="timezonesArray"
      :close-on-select="true"
      placeholder="Select a Timezone"
      class="select" 
      name="timezone">
    </multiselect>
  </div>
  <p v-show="errors.has('timezone')" class="help is-danger">{{ errors.first('timezone') }}</p>
</div>

<div class="field">
  <label class="label">
    Birthday
    <a class="delete" v-if="birthday" @click="birthday = null"></a>
  </label>
	<div class="control has-icons-left">
	  <flat-pickr name="birthday" v-model="birthday" class="input" placeholder="Select Date"></flat-pickr>
	  <span class="icon is-small is-left">
	    <i class="fa fa-calendar"></i>
	  </span>
	</div>
  <p v-show="errors.has('birthday')" class="help is-danger">{{ errors.first('birthday') }}</p>
</div>

<div class="field">
  <label class="label">Gender</label>
  <div class="control">
    <multiselect
      v-model="gender"
      :options="genderArray"
      :close-on-select="true"
      placeholder="Select a Gender"
      class="select" 
      name="gender">
    </multiselect>
  </div>
  <p v-show="errors.has('gender')" class="help is-danger">{{ errors.first('gender') }}</p>
</div>

<div class="field">
  <label class="label">Location</label>
  <div class="control">
    <input class="input" type="text" placeholder="i.e. City, State/Province, Country" v-model="location" name="location" v-validate="'max: 150'">
  </div>
  <p v-show="errors.has('location')" class="help is-danger">{{ errors.first('location') }}</p>
</div>

<div class="field">
  <div class="control">
    <button class="button is-primary" @click="validateBeforeSubmit">Update</button>
  </div>
</div>

</div>
</template>

<script>
var moment = require('moment-timezone');
import Multiselect from 'vue-multiselect'
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import store from '../../../../store.js'

export default {
    props: ['user'],
  	components: {Multiselect, flatPickr},
    data() {
      return {
        timezonesArray: moment.tz.names(),
        genderArray: ['Female', 'Male', 'Undisclosed'],
        
        aboutMe: '',
        timezone: null,
        birthday: null,
        gender: null,
        location: ''
      }
    },
    watch: {
      user: function () { 
        this.updateSettings()
      }
    },
    methods: {
      	validateBeforeSubmit() {
	        this.$validator.validateAll().then((result) => {
	          if (result) { this.updateProfile() }
	        });
      	},
        updateSettings() {
          this.aboutMe = this.user.about_me;
          this.timezone = this.user.timezone;
          this.gender = this.user.gender;
          this.birthday = this.user.birthday;
          this.location = this.user.location;
        },
  	  	updateProfile() {
    			axios.patch(('/api/users/' + this.user.id ), {
              about_me: this.aboutMe,
              timezone: this.timezone,
              gender: this.gender,
              birthday: this.birthday,
              location: this.location,
              update: 'profile'
            })
    		    .then((response) => { 
    		    	if((response.status === 401) || (response.status === 403) || (response.status === 404)) { 
                this.$router.replace({ name: 'Home'}) 
                flash('Unauthorized to Update Settings', 'error');
              }
              else if(response.status === 200) { 
                flash('Settings Updated', 'success');
                this.$store.commit('updateUser', response.data);
              }
    		    })
    		    .catch((error) => this.$router.replace({ name: 'Home'}) );
  	  	}
  	},
    created() {
      this.updateSettings()
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>