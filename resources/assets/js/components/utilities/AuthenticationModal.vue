<template>
<div class="modal" v-bind:class="{ 'is-active': modalState }">
  <div class="modal-background" @click="close"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Login or Register</p>
      <button class="delete" aria-label="close" @click="close"></button>
    </header>
    <section class="modal-card-body">
		<p>Create an account to track, discover, review and discuss video games.</p>
    </section>
    <footer class="modal-card-foot">
		<router-link class="button is-dark" :to="{name: 'Login'}">
			Login
		</router-link>
		<router-link class="button is-primary" :to="{name: 'Register'}">
			Register
		</router-link>
    </footer>
  </div>
</div>

</template>

<script>
export default {
	props: [ 'modalState' ],
	methods: {
    	close() {
      		this.$emit('close')
    	}
	}
}
</script>