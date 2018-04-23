<template>
<div>
	<section class="hero is-primary">
		<div class="hero-bg-games">
		  <div class="hero-body">
		    <div class="container">
		      <h1 class="title">
		      	<span class="has-background-primary">
		      		Browse Games
		      	</span>
		      </h1>
			  <h2 class="subtitle">
				<span class="has-text-grey-dark is-size-6">
					<span class="has-background-primary">
						Have a game in mind?
					</span>
				</span>
			  </h2>
		    </div>
		  </div>
		</div>
	</section>
	<section class="section">
		<div class="container">
			<div class="columns is-mobile is-multiline">
				<div class="column is-12">
					<article class="message">
					  <div class="message-body">
					    Curious on how games are organized/consolidated? Please take a look at our <router-link :to="{name: 'Faq'}"><strong>FAQ</strong></router-link> to get a better understanding.
					  </div>
					</article>
				</div>
				<div class="column is-12">
					<div class="field is-grouped">
					  	<div class="control is-expanded">
					    	<input class="input is-medium" type="text" placeholder="Search Games..." v-model="search" @keyup.enter="applyFilters">
						</div>
						<div class="control">
							<a class="button is-primary is-medium" @click="applyFilters">
							    <span class="icon">
							      <i class="fas fa-search"></i>
							    </span>
							    <span class="is-hidden-touch">Search</span>
							</a>
						</div>

						<div class="control is-hidden-desktop">
							<a class="button is-medium is-danger" @click="hideFilters=!hideFilters" v-bind:class="{ 'is-outlined': hideFilters }">
								<span class="icon is-small">
								  <i class="fas fa-filter"></i>
								</span>
							</a>
						</div>
						
						<div class="field has-addons">
							<div class="control">
								<a class="button is-medium is-primary" v-bind:class="{ 'is-outlined': detailedView }" @click='toggleView'>
									<span class="icon is-small">
									  <i class="fas fa-th"></i>
									</span>
								</a>
							</div>
							<div class="control">
								<a class="button is-medium is-primary" v-bind:class="{ 'is-outlined': simpleView }" @click='toggleView'>
									<span class="icon is-small">
									  <i class="fas fa-list"></i>
									</span>
								</a>
							</div>
						</div>

					</div>
				</div>
				<div class="column is-3-desktop" v-bind:class="{ 'is-hidden-touch': hideFilters }">
				
					<aside class="menu box">
					  <p class="menu-label">
					    Minimum Average Score
					  </p>
						<input id="sliderWithValue" class="slider has-output is-fullwidth is-primary" min="0" max="100" step="1" type="range" v-model="score" @change="applyFilters">
						<output for="sliderWithValue">{{ score }}</output>
					  <p class="menu-label">
					    Platforms
					  </p>
			              <multiselect
			                v-model="selectedPlatforms"
			                :options="platforms"
			                :close-on-select="true"
			                :multiple="true"
			                placeholder="Select Platforms"
			                track-by="name"
			                label="name"
			                class="select" name="selectedPlatforms"
			                @input="platformQuery">
			              </multiselect>
					  <p class="menu-label">
					    Genres
					  </p>
			              <multiselect
			                v-model="selectedGenres"
			                :options="genres"
			                :close-on-select="true"
			                :multiple="true"
			                placeholder="Select Genres"
			                track-by="name"
			                label="name"
			                class="select" name="selectedGenres"
			                @input="genreQuery">
			              </multiselect>
					  <p class="menu-label">
					    Developer
					  </p>
			              <multiselect
			                v-model="selectedDeveloper"
			                :options="developers"
			                :close-on-select="true"
			                placeholder="Select a Developer"
			                track-by="name"
			                label="name"
			                class="select" name="selectedDeveloper"
			                @input="developerQuery">
			              </multiselect>
					  <p class="menu-label">
					    Publisher
					  </p>
			              <multiselect
			                v-model="selectedPublisher"
			                :options="publishers"
			                :close-on-select="true"
			                placeholder="Select a Publisher"
			                track-by="name"
			                label="name"
			                class="select" name="selectedPublisher"
			                @input="publisherQuery">
			              </multiselect>
			          <p class="menu-label">
					    Sort
					  </p>
					  	<div class="field">
						  	<div class="control has-icons-left">
							  <div class="select is-fullwidth">
							    <select v-model="sort">
							      <option value="popular" @click="applyFilters" selected="">Popularity</option>
							      <option value="rating" @click="applyFilters">Average Rating</option>
							      <option value="recent" @click="applyFilters">Recently Added</option>
							    </select>
							  </div>
							  <span class="icon is-medium is-left">
							    <i class="fas fa-sort-amount-down"></i>
							  </span>
							</div>
					  	</div>
						<hr>
						<span class="button is-danger is-fullwidth" @click="clearFilters">Clear Filters</span> 
					</aside>

				</div>
				<div class="column is-12-mobile is-12-tablet is-9-desktop">

						<div v-infinite-scroll="loadMore" infinite-scroll-disabled="busy" :infinite-scroll-immediate-check="false">

						    <div v-if="gamesLoading" class="columns">
						    	<div class="column">
						    		<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
						    	</div>
							</div>
							<div class="columns is-mobile is-multiline is-centered" v-else-if="games.length > 0">
							
								<div class="column" v-if="simpleView" v-for="game in games" :key="game.id" style="min-width: 155px; max-width: 175px">
							        <div class="card item-shadow ">
							          <div class="card-image imageFade">
							            <figure class="image tooltip is-tooltip-primary" :data-tooltip="game.title" >
						            		<a :href="'games/' + game.id">
						            			<div class="text-container">
							              			<img :src="$store.state.cdnURL + 'games/icons/' + game.icon" :alt="game.title" class="imageFade">
							              			<div class="bottom-left is-hidden-tablet ">{{ game.title }}</div>
						              			</div>
						              		</a>
							            </figure>
							          </div>
							        </div>
						        </div>

						        <div class="column" v-if="detailedView" >
									<div class="columns is-mobile is-multiline is-centered">						        	
									  <div class="column" style="min-width: 350px;" v-for="(game, index) in games" :key="game.id">
									      <div class="columns is-gapless is-mobile card" style="max-height: 175px;">
									        <div class="column is-narrow" >
									          <figure class="card image imageFade item-shadow" style="min-width: 140px; max-width: 175px">
									          	<router-link :to="{name: 'Game', params: { id: game.id }}">
										            <div class="text-container">
										              <img :src="$store.state.cdnURL + 'games/icons/' + game.icon" :alt="game.title">
										              <div class="bottom-left">{{ game.title }}</div>
										            </div>
									        	</router-link>
									          </figure>
									        </div>
									        <div class="column" style="background-color: #f5f5f5">
									          <div class="content is-size-7">
									            <div class="game-detail-header">
									                <div class="tags">
									                  <span class="tag is-info">
									                  	{{ game.developer.name }}
									                  </span>
									                  <span class="tag is-primary">
									                  	{{ game.score ? (game.score + '%') : 'No Score'  }}
									                  </span>
									                </div>
									            </div>
									            <div class="game-detail-content">
									            	{{ game.synopsis }}
									            </div>
									          </div>
									        </div>
									      </div>
									    </div> 
									</div> 
						        </div>

						    </div>
							<article v-else class="message">
							  <div class="message-body">No Games Found.</div>
							</article>

						    <div v-if="newGamesLoading" class="columns">
						    	<div class="column">
						    		<center><i class="fas fa-spinner fa-spin fa-2x"></i></center>
						    	</div>
							</div>

						</div>

				</div>
			</div>
		</div>
	</section>
</div>
</template>

<script>
import Multiselect from 'vue-multiselect'

export default {
  components: {Multiselect},
  data() {
    return {
      games: [],
      currentPage: 0,
      lastPage: -1,

      gamesLoading: true,
      newGamesLoading: false,
      detailedView: false,
      simpleView: true,
      hideFilters: true,

      developers: [],
      publishers: [],
      platforms: [],
      genres: [],

      selectedDeveloper: null,
      selectedGenres: [],
      selectedPlatforms: [],
      selectedPublisher: null,
     
      search:'',
      score: 0,
      developerFilter: '',
      publisherFilter: '',
      platformsFilter: [],
      genresFilter: [],
      sort: 'popular',

      busy: true,
    }
  },
  filters: {
  	truncate: function(string, value) {
    	return string.substring(0, value) + '...';
    },
  	percentage: function(value, decimals) {
		  if(!value) {
		    value = 'N/A';
		  }
		  if(!decimals) {
		    decimals = 2;
		  }
		  value = (value/10) * 100;
		  value = Math.round(value * Math.pow(10, decimals)) / Math.pow(10, decimals);
		  value = value + '%';
		return value;
    }
  },
  methods: {
  	toggleView() {
  		this.detailedView = !this.detailedView;
  		this.simpleView = !this.simpleView;
  	},
	applyFilters() {
		var platformsFilter = JSON.stringify(this.platformsFilter)
		var genresFilter = JSON.stringify(this.genresFilter)
		if(this.simpleView) { var view = 'simple' }
		else { var view = 'detail' }
		this.$router.replace({query: {
			search: this.search, 
			developer: this.developerFilter, 
			publisher: this.publisherFilter, 
			platforms: platformsFilter, 
			genres: genresFilter, 
			score: this.score,
			sort: this.sort, 
			view: view 
		} });
	},
	clearFilters() {
		this.$router.replace({ name: 'Games'});
	},
	developerQuery() {
    	if(this.selectedDeveloper) {
    		this.developerFilter = this.selectedDeveloper.id;
    	}
    	else { this.developerFilter = ''}
    	this.applyFilters();
	},
	platformQuery() {
    	if(this.selectedPlatforms) {
    		this.platformsFilter = this.selectedPlatforms.map(a => a.id);
    	}
    	else { this.platformsFilter = []}
    	this.applyFilters();
	},
	publisherQuery() {
    	if(this.selectedPublisher) {
    		this.publisherFilter = this.selectedPublisher.id;
    	}
    	else { this.publisherFilter = ''}
    	this.applyFilters();
	},
	genreQuery() {
    	if(this.selectedGenres) {
    		this.genresFilter = this.selectedGenres.map(a => a.id);
    	}
    	else { this.genresFilter = []}
    	this.applyFilters();
	},
	loadMore: function() {
      this.busy = true;
      setTimeout(() => {
		if(this.currentPage < this.lastPage) 
		{ 
			this.newGamesLoading = true;
			this.currentPage++

			var query = '?page=' + (this.currentPage)
			if(location.search) {
				query = location.search + '&page=' + (this.currentPage)
			}
			axios.get('/api/games' + query)
		    .then((response) => { 
		    	Array.prototype.push.apply(this.games, response.data.data);
		    	this.newGamesLoading = false; 
		    })  
		}
		this.busy = false;	
      }, 500);
    }
  },
  created() {
  	if (this.$route.query.score >= 0 &&  this.$route.query.score <= 100 ) { this.score = this.$route.query.score }
  	this.search = this.$route.query.search;
  	if (this.$route.query.sort) { this.sort = this.$route.query.sort; }
  	if (this.$route.query.view == 'detail') { this.simpleView=false; this.detailedView=true; }
  	if (this.$route.query.view == 'simple') { this.simpleView=true; this.detailedView=false; }
  	
	axios.get('/api/games' + location.search )
    .then((response) => { 
    	this.games = response.data.data; 
    	this.currentPage = response.data.current_page; 
    	this.lastPage = response.data.last_page; 
    	this.gamesLoading = false; 
    })
    .catch((error) => console.log("Games array not updated."));

    axios.get('/api/developers')
    .then((response) => { 
    	this.developers = response.data; 
    	if(this.$route.query.developer) {
    		var developer = this.developers.filter(developer => developer.id == this.$route.query.developer)[0]
    		this.selectedDeveloper = (developer) 
    		this.developerFilter = developer.id
    	}
    })
    .catch((error) => console.log("Developers array not updated."));

    axios.get('/api/publishers')
    .then((response) => { 
    	this.publishers = response.data; 
    	if(this.$route.query.publisher) {
    		var publisher = this.publishers.filter(publisher => publisher.id == this.$route.query.publisher)[0]
    		this.selectedPublisher = (publisher) 
    		this.publisherFilter = publisher.id
    	}
    })
    .catch((error) => console.log("Publishers array not updated."));

    axios.get('/api/platforms')
    .then((response) => { 
    	this.platforms = response.data; 
    	//console.log(JSON.parse(JSON.stringify(response.data)))
    	if(this.$route.query.platforms) {
    		var platformIDArray = JSON.parse(this.$route.query.platforms)
    		var platformObjectArray = []

    		platformIDArray.forEach(function(element) {
    			var platformObject = response.data.find(platform => platform.id == element)
    			if(platformObject) { platformObjectArray.push(platformObject) }
			});
    		this.selectedPlatforms = platformObjectArray 
    		this.platformsFilter = platformIDArray
    	}
    })
    .catch((error) => console.log("Platforms array not updated."));

    axios.get('/api/genres')
    .then((response) => { 
    	this.genres = response.data; 
    	if(this.$route.query.genres) {
    		var genreIDArray = JSON.parse(this.$route.query.genres)
    		var genreObjectArray = []

    		genreIDArray.forEach(function(element) {
    			var genreObject = response.data.find(genre => genre.id == element)
    			if(genreObject) { genreObjectArray.push(genreObject) }
			});
    		this.selectedGenres = genreObjectArray 
    		this.genresFilter = genreIDArray
    	}
    })
    .catch((error) => console.log("Genres array not updated."));

    setTimeout(() => { this.busy = false; }, 500);

  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>