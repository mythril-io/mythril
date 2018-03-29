<template>
	<!-- Footer -->

	<footer class="footer">
	  <div class="container">
	    <div class="content has-text-centered">
	      <p>
	        &copy; <strong>Mythril</strong> 2018 - All Rights Reserved. <br>
	        <a href="#">About Us</a> | <a href="#">Terms of Service</a> | <a href="#">Privacy Policy</a>
	      </p>
	      <p>
	      	<a class="icon is-medium" href="#">
	          <i class="fab fa-discord fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="#">
	          <i class="fab fa-twitter fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="#">
	          <i class="fab fa-facebook-square fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="#">
	          <i class="fab fa-youtube fa-lg"></i>
	        </a>
	        <a class="icon is-medium" href="#">
	          <i class="fab fa-instagram fa-lg"></i>
	        </a>
<!-- 	        <a class="icon is-medium" href="#">
	          <i class="fab fa-github-alt fa-lg"></i>
	        </a> -->
	      </p>
	      <p v-html="koFiButton"></p>
	    </div>
	  </div>
	</footer>

</template>

<script>
	export default {
		data() {
			return {
				koFiButton: ''
			}
		},
		created() {
			kofiwidget2.init('Buy Us a Coffee', '#ff5d61', 'P5P4BB7L');
			this.koFiButton = kofiwidget2.getHTML()
		}
	}
</script>