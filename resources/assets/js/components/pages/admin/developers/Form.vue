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
          <label class="label">Country</label>
          <p class="control has-icons-left">
            <input :class="{'input': true, 'is-danger': errors.has('country') }" type="text" placeholder="Country"  v-model="country" name="country" v-validate="'required'">
            <span class="icon is-small is-left">
              <i class="fas fa-globe"></i>
            </span>
            <span v-show="errors.has('country')" class="help is-danger">{{ errors.first('country') }}</span>
          </p>
        </div>
        <div class="field">
          <label class="label">Description</label>
          <p class="control has-icons-left">
            <textarea :class="{'textarea': true, 'is-danger': errors.has('description') }" placeholder="Description" v-model="description" name="description"  v-validate="'required'"></textarea>
            <span v-show="errors.has('description')" class="help is-danger">{{ errors.first('description') }}</span>
          </p>
        </div>
	    <div class="field">
	      <p class="control">
	        <button class="button is-primary" @click.prevent="validateBeforeSubmit" :disabled="errors.any()">{{ action }} Developer</button>
	      </p>
	    </div>
	</form>
</template>

<script>
export default {
	props:['developer', 'action'],
	data() {
    	return {
    		id: '',
			name: '',
			country: '',
			description: '',
    	}
	},
	created(){
		this.updateFormData();
	},
	watch: {
	// whenever release changes, this function will run
		developer: function () {
			this.updateFormData();
		}
	},
	methods: {
		validateBeforeSubmit() {
			this.$validator.validateAll().then((result) => {
		  		if (result) { 
		  			if(this.developer) {
		  				this.$emit('send', {id: this.id, name: this.name, country: this.country, description: this.description}) 
		  			} else {
		  				this.$emit('send', {name: this.name, country: this.country, description: this.description})
		  			}
		  			
		  		}
			});
		},
		updateFormData() {
			if(this.developer) {
				this.id = this.developer.id;
				this.name = this.developer.name;
				this.country = this.developer.country;
				this.description = this.developer.description;
			}	
		},
	}
}	
</script>