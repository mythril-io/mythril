<template>
<div>

	<div class="field">
		<markdown-editor 
			v-model="content" 
			name="recommendation"
			v-validate="'required|min: 200'">
		</markdown-editor>
		<p v-show="errors.has('recommendation')" class="help is-danger">{{ errors.first('recommendation') }}</p>
	</div>
	<br>
	<div class="field">
	  <div class="control">
	    <button class="button is-primary" @click="validateBeforeSubmit" :disabled="errors.any()">Submit Recommendation</button>
	  </div>
	</div>

</div>
</template>

<script>
import VeeValidate from 'vee-validate';
import MarkdownEditor from '../../utilities/MarkdownEditor.vue'

export default {
	components: {MarkdownEditor},
	props: ['recommendation'],
	data() {
    	return {
    		content: this.recommendation ? this.recommendation.content : 'Write a short description about your recommendation (Minimum 200 characters)',
    	}
	},
	watch: {
	  recommendation: function () {
	    if(this.recommendation) { 
	    	this.content = this.recommendation.content;
	    }
	  }
	},
	methods: {
      	validateBeforeSubmit() {
	        this.$validator.validateAll().then((result) => {
	          if (result) { 
	          	var recommendation = {
	          		'content': this.content,
	          	}
	          	this.$emit('submit', recommendation);
	          }
	        });
      	}
    }  	
}

</script>