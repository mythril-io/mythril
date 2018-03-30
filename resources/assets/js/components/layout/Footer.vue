<template>
	<!-- Footer -->

	<footer class="footer">
	  <div class="container">
	    <div class="content has-text-centered">
	      <p>
	        &copy; <strong>Mythril</strong> 2018<br>
	        	<router-link :to="{name: 'Faq'}">F.A.Q.</router-link> | 
	        	<a href="#">Terms of Service</a> | 
	        	<a href="#">Privacy Policy</a>
	      </p>
	      <p>
	      	<a class="icon is-medium" href="https://discord.gg/VMp7UNJ" target="_blank">
	          <i class="fab fa-discord fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="https://twitter.com/mythril_io" target="_blank">
	          <i class="fab fa-twitter fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="#">
	          <i class="fab fa-facebook-square fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="https://www.youtube.com/channel/UCSfbSlgiS3ghdcHihNJZ8TA" target="_blank">
	          <i class="fab fa-youtube fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="#">
	          <i class="fab fa-instagram fa-lg"></i>
	        </a>
<!-- 	        <a class="icon is-medium" href="#">
	          <i class="fab fa-github-alt fa-lg"></i>
	        </a> -->
	      </p>
	      <p v-html="kofiButton"></p>
	    </div>
	  </div>
	</footer>

</template>

<script>
	export default {
		data() {
			return {
				kofiButton: ''
			}
		},
		created() {
			kofiwidget2.init('Buy Us a Coffee', '#ff5d61', 'P5P4BB7L');
			this.kofiButton = kofiwidget2.getHTML()
		}
	}
</script>