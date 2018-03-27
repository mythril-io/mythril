<template lang="html">
  	<div class="pageloader" v-bind:class="{ 'is-active': loading }">
  		<span class="title">Verifying...</span>
  	</div>
</template>

<script>
export default {
	data() {
		return {
		  loading: 'true'
		}
	},
	created: function () {
		setTimeout(function () { this.verify() }.bind(this), 2000)
	},
	methods: {
		verify() {
	        axios.get('/api/auth/verify/' + this.$route.params.code)
	            .then(
	                (response) => {
	                	console.log(response.data.success)
	                	if(response.data.success) {
							this.$router.replace({ name: 'Login'})
							flash('Email has been verified!', 'success');
	                	}
	                	else {
		            		this.$router.replace({ name: 'Home'})
		            		flash(response.data.error, 'error')
	                	}
	                }
	            )
	            .catch((error) => { 
	            	this.$router.replace({ name: 'Home'})
	            });
		}
	}
}
</script>
