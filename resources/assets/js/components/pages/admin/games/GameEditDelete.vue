<template>
<div>
	
	<h1 class="title">Edit/Delete Games</h1>
    <article class="message">
      <div class="message-header"><p>Step 1: Select a Game</p></div>
      <div class="message-body">

      	<div class="field">
		  <p class="control">
		    <multiselect
		      v-model="selectedGame"
		      :options="games"
		      :close-on-select="true"
		      placeholder="Select a Game"
		      track-by="title"
		      label="title"
		      class="select" name="selectedGame">
		    </multiselect>
		  </p>
		</div>


		<div v-if="selectedGame">
			<a class="button is-primary" @click="toggleEdit(selectedGame)">Edit</a>
	        <a class="button is-danger" @click="toggleDelete(selectedGame)">Delete</a>
		</div>

      </div>
  	</article>

      <delete-form 
        v-if="deleteGame" 
        :resource="'game (' + deleteGame.title +')'" 
        @confirm="deleteGameSend"
        @close="deleteGame = null">
      </delete-form>

    <article class="message" v-if="editGame">
      <div class="message-header"><p>Step 2: Edit Game</p></div>
      <div class="message-body">

        <game-edit-form :game="editGame"></game-edit-form>

      </div>
  	</article>

</div>
</template>

<script>
import NProgress from 'nprogress'
import Multiselect from 'vue-multiselect'
import DeleteForm from '../../../utilities/DeleteForm.vue'
import GameEditForm from './GameEditForm.vue'

export default {
	components: {Multiselect, DeleteForm, GameEditForm},
	data() {
    	return {
    		selectedGame: null,
    		games: [],

    		deleteGame: null,
    		editGame: null,
    	}
	},
	created() {
		axios.get('/api/admin/games')
        .then((response) => { this.games = response.data; })
        .catch((error) => console.log("Games array not updated."));
	},
	methods: {
      	toggleDelete(game) {
      		this.deleteGame = game;
      		this.editGame = null;
      	},
      	toggleEdit(game) {
      		this.editGame = game;
      		this.deleteGame = null;
      	},
      	deleteGameSend() {
	        var success = true;
	        NProgress.start();
	        axios.delete(('/api/admin/games/' + this.deleteGame.id + '/delete'))
	        .catch((error) => { 
	            NProgress.done();
	            success = false;
	            flash('Unable to Delete Game.', 'error')
	        })
	        .then((response) => {
	            if(success) {
	              NProgress.done();
	              flash('Game Deleted Successfully.', 'success')
	              this.deleteGame = null;
	            }
	        });
      	}
	}
}	
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>