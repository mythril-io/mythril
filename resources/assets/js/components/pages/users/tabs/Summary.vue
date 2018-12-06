<template>
<div>
	<div class="columns">
		<div class="column is-8 is-9-widescreen">
		    <div class="content">
		        <h2>About Me</h2>
		    </div>
			
			<div class="columns">
				<div class="column is-narrow" v-if="details">
					<div class="notification is-light">
						<ul>
							<li v-if="this.displayedUser.birthday">
								<span class="icon">
								  <i class="fas fa-birthday-cake"></i>
								</span>
								<em>{{ this.displayedUser.birthday | dateFormat }}</em>
							</li>
							<li v-if="this.displayedUser.location">
								<span class="icon">
								  <i class="fas fa-compass"></i>
								</span>
								<em>{{ this.displayedUser.location }}</em>
							</li>
							<li v-if="this.displayedUser.gender">
								<span class="icon">
								  <i class="fas fa-user"></i>
								</span>
								<em>{{ this.displayedUser.gender }}</em>
							</li>
						</ul>
					</div>
				</div>
				<div class="column">
					<article class="message">
			  			<div class="message-body">
							<span v-if="this.displayedUser.about_me" v-html="compiledMarkdown" class="content"></span>
							<span v-else>This user hasn't written anything about themselves.</span>
						</div>
					</article>
				</div>
			</div>
			
			<div v-if="this.orderedLibrary.length > 0">
			    <div class="content">
			        <h2>Currently Playing</h2>
			    </div>
				<div class="columns is-multiline is-mobile">
					<div class="column is-3-mobile is-3-tablet is-3-desktop is-2-widescreen" v-for="library in orderedLibrary" :key="library.id">
						<router-link :to="{name: 'Game', params: { id: library.game.id }}">
							<b-tooltip :label="getLibraryTitle(library)">
								<figure class="card image imageFade item-shadow">
									<div class="text-container">
										<img v-lazy="$store.state.cdnURL + 'games/icons/' + library.game.icon" :alt="library.game.title">
										<div class="bottom-left is-hidden-tablet ">{{ library.game.title }}</div>
									</div>
								</figure>
							</b-tooltip>
	                    </router-link>
                    </div>
				</div>
			</div>
                   
		</div>

		<div class="is-divider-vertical is-hidden-mobile"></div>

		<div class="column">
		    <div class="content">
		        <h2>Favourite Games</h2>
		    </div>
                    <div class="columns is-multiline is-mobile" v-if="displayedUser.favourites.length > 0">
                        <div class="column is-3-mobile is-6-tablet" v-for="favourite in orderedFavourites">
                        	<router-link :to="{name: 'Game', params: { id: favourite.game.id }}">
								<b-tooltip :label="favourite.release.alternate_title ? favourite.release.alternate_title : favourite.game.title">
									<figure class="card image imageFade item-shadow">
										<div class="text-container">
											<img v-lazy="$store.state.cdnURL + 'games/icons/' + favourite.game.icon" :alt="favourite.game.title">
											<div class="bottom-left is-hidden-tablet ">{{ favourite.game.title }}</div>
										</div>
									</figure>
								</b-tooltip>
                            </router-link>
                        </div>
                    </div>
				    <article class="message is-warning" v-else>
				      <div class="message-body">
				        No favourites.
				      </div>
				    </article>
		</div>
	</div>

</div>
</template>

<script>
import moment from 'moment';
var md = require('markdown-it')();

export default {
    props: ['displayedUser'],
	computed: {
		details: function () {
		  return this.displayedUser.birthday || this.displayedUser.location || this.displayedUser.gender
		},
		compiledMarkdown: function () {
			return md.render(this.displayedUser.about_me);
		},
		orderedLibrary: function () {
			var playingEntries = _.filter(this.displayedUser.libraries, ['play_status_id', 1]);
			return _.orderBy(playingEntries, 'game_id', 'desc')
		},
		orderedFavourites: function () {
			return _.orderBy(this.displayedUser.favourites, 'game.title', 'asc');
		}
	},
	filters: {
		dateFormat(date) {
			return moment(date).format("MMM Do, YYYY");
		}
	},
	methods: {
		getLibraryTitle(libraryItem) {
			return libraryItem.release.alternate_title ? libraryItem.release.alternate_title : libraryItem.game.title
		}
	}
}
</script>