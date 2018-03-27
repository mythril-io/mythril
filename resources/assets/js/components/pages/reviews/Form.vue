<template>
<div>
	
	<div class="field">
		<markdown-editor 
			v-model="content" 
			name="content"
			v-validate="'required|min: 750'">
		</markdown-editor>
		<p v-show="errors.has('content')" class="help is-danger">{{ errors.first('content') }}</p>
	</div>

	<div class="field">
		<label class="label">Summary</label>
		<div class="control has-icons-left is-expanded">
    	    <span class="icon is-small is-left">
		    	<i class="fas fa-comment-alt"></i>
		    </span>
			<input class="input" type="text" v-model="summary" name="summary" placeholder="Write a short summary about your review (Maximum 255 characters)" 
				v-validate="'required|min: 60|max: 255'">
		</div>
		<p v-show="errors.has('summary')" class="help is-danger">
		  	{{ errors.first('summary') }}
		</p>
	</div>

	<div class="field">
		<label class="label">Score</label>
	    <div class="control has-icons-left is-expanded">
    	    <span class="icon is-small is-left">
		    	<i class="fas fa-star"></i>
		    </span>
	    	<input class="input" type="number" min="1" max="100" name="score" placeholder="Enter a number between 1-100" v-model="score" v-validate="'numeric|between:1,100|required'">
	    </div>
	  	<p v-show="errors.has('score')" class="help is-danger">{{ errors.first('score') }}</p>
	</div>

	<br>

	<div class="field">
	  <div class="control">
	    <button class="button is-primary" @click="validateBeforeSubmit" :disabled="errors.any()">Submit Review</button>
	  </div>
	</div>

</div>
</template>

<script>
import MarkdownEditor from '../../utilities/MarkdownEditor.vue'
import VeeValidate from 'vee-validate';

export default {
	components: {MarkdownEditor},
	props: ['review'],
	data() {
    	return {
    		content: this.review ? this.review.content : '## Step 3' + '\n\n'  +'Write Your Review!',
    		summary: this.review ? this.review.summary : '',
    		score: this.review ? this.review.score : null
    	}
	},
	watch: {
	  review: function () {
	    if(this.review) { 
	    	this.content = this.review.content;
	    	this.summary = this.review.summary;
	    	this.score = this.review.score;
	    }
	  }
	},
	methods: {
      	validateBeforeSubmit() {
	        this.$validator.validateAll().then((result) => {
	          if (result) { 
	          	var review = {
	          		'content': this.content,
	          		'summary': this.summary,
	          		'score': this.score
	          	}
	          	this.$emit('submit', review);
	          }
	        });
      	}
    }  	
}

</script>