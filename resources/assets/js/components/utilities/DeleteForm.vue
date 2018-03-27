<template>

	<article class="message is-danger">
		<div class="message-header">
			<p>Delete Confirmation</p>
		    <button class="delete" aria-label="delete" @click="$emit('close')"></button>
		</div>
		<div class="message-body">
		    Are you sure you want to delete this {{ this.resource }}? This action cannot be undone.
		    <br><br>

			<div class="field is-grouped">
			  <p class="control">
			    <input class="input" v-model="input" type="text" placeholder="Type in DELETE...">
			  </p>
			  <p class="control">
			    <a class="button is-danger" @click.prevent="checkDelete">
			      Delete
			    </a>
			  </p>
			</div>
		</div>
	</article>

</template>

<script>
export default {
	props: ['resource'],
	data() {
    	return {
    		deleteConfirm: 'DELETE',
    		input: ''
    	}
	},
	methods: {
      	checkDelete() {
      		if(this.deleteConfirm === this.input) 
      		{
      			this.$emit('confirm')
      		}
      		else { flash('Please type in "DELETE".', 'error') }
      	}
	}
}	
</script>