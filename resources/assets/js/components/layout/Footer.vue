<template>
	<!-- Footer -->

	<footer class="footer">
	  <div class="container">
	    <div class="content has-text-centered">
	      <p>
	        &copy; <strong>Mythril</strong> 2018-2019<br>
	        	<router-link :to="{name: 'Faq'}">FAQ</router-link> | 
	        	<router-link :to="{name: 'TermsOfService'}">Terms of Service</router-link> | 
	        	<router-link :to="{name: 'PrivacyPolicy'}">Privacy Policy</router-link>
	      </p>
	      <p>
					<a href="https://discord.gg/VMp7UNJ" target="_blank">
						<b-icon 
								pack="mdi"
								icon="discord"
								size="is-medium"
								custom-size="mdi-24px">
						</b-icon>
					</a>
					<a href="https://twitter.com/mythril_io" target="_blank">
						<b-icon 
								pack="mdi"
								icon="twitter"
								size="is-medium"
								custom-size="mdi-24px">
						</b-icon>
					</a>
					<!-- <a href="https://www.facebook.com/Mythril-1856001717764618" target="_blank">
						<b-icon 
								pack="mdi"
								icon="facebook"
								size="is-medium"
								custom-size="mdi-24px">
						</b-icon>
					</a> -->
					<a href="https://www.youtube.com/channel/UCSfbSlgiS3ghdcHihNJZ8TA" target="_blank">
						<b-icon 
								pack="mdi"
								icon="youtube"
								size="is-medium"
								custom-size="mdi-24px">
						</b-icon>
					</a>
					<a href="https://www.instagram.com/mythril_io/" target="_blank">
						<b-icon 
								pack="mdi"
								icon="instagram"
								size="is-medium"
								custom-size="mdi-24px">
						</b-icon>
					</a>
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