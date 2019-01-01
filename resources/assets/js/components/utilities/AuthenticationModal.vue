<template>
	<div class="modal-card" style="width: auto">
		<header class="modal-card-head">
			<p class="modal-card-title">Login or Register</p>
		</header>
		<section class="modal-card-body">
			<p>Create an account to track, discover, review and recommend video games.</p>
		</section>
		<footer class="modal-card-foot">
			<button class="button is-dark" @click="login()">Login</button>
			<button class="button is-primary" @click="register()">Register</button>
		</footer>
	</div>
</template>

<script>
export default {
	methods: {
    	login() {
			this.$router.push({ name: 'Login' });
			this.$emit('close');
			},
    	register() {
			this.$router.push({ name: 'Register' });
			this.$emit('close');
    	}
	}
}
</script>