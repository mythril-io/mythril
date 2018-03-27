<template>
	<form>
	    <div class="field">
	      <label class="label">Name</label>
	      <p class="control has-icons-left">
	        <input :class="{'input': true, 'is-danger': errors.has('name') }" type="text" placeholder="Name"  v-model="name" name="name" v-validate="'required'">
	        <span class="icon is-small is-left">
	          <i class="fas fa-folder"></i>
	        </span>
	        <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
	      </p>
	    </div>
	    <div class="field">
	      <label class="label">Acronym</label>
	      <p class="control has-icons-left">
	        <input :class="{'input': true, 'is-danger': errors.has('acronym') }" type="text" placeholder="Acronym"  v-model="acronym" name="acronym" v-validate="'required'">
	        <span class="icon is-small is-left">
	          <i class="fas fa-file-alt"></i>
	        </span>
	        <span v-show="errors.has('acronym')" class="help is-danger">{{ errors.first('acronym') }}</span>
	      </p>
	    </div>
	    <div class="field">
	      <p class="control">
	        <button class="button is-primary" @click.prevent="validateBeforeSubmit" :disabled="errors.any()">{{ action }} Platform</button>
	      </p>
	    </div>
	</form>
</template>

<script>
export default {
	props:['platform', 'action'],
	data() {
    	return {
    		id: '',
			name: '',
			acronym: '',
    	}
	},
	created(){
		this.updateFormData();
	},
	watch: {
	// whenever release changes, this function will run
		platform: function () {
			this.updateFormData();
		}
	},
	methods: {
		validateBeforeSubmit() {
			this.$validator.validateAll().then((result) => {
		  		if (result) { 
		  			if(this.platform) {
		  				this.$emit('send', {id: this.id, name: this.name, acronym: this.acronym}) 
		  			} else {
		  				this.$emit('send', {name: this.name, acronym: this.acronym})
		  			}
		  			
		  		}
			});
		},
		updateFormData() {
			if(this.platform) {
				this.id = this.platform.id;
				this.name = this.platform.name;
				this.acronym = this.platform.acronym;
			}	
		},
	}
}	
</script>