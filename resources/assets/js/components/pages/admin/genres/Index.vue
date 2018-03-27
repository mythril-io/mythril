<template>
	<div>
		<h1 class="title">Genres</h1>
    <div class="content">
      <blockquote>Genres currently in Database: <strong>{{ genres.length }}</strong></blockquote>
    </div>
    <div class="content">
      <div v-if="genres.length > 0"> 
          <table class="table is-striped">
            <thead>
              <tr>
                <th>Genre</th>
                <th width="10%"></th>
                <th width="10%"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="genre in genres">
                <td valign="middle">{{ genre.name }} <span class="tag is-light is-medium" v-if="genre.acronym">({{ genre.acronym }})</span></td>
                <td><a class="button is-primary" @click="toggleEdit(genre)">Edit</a></td>
                <td><a class="button is-danger" @click="toggleDelete(genre)">Delete</a></td>
              </tr>
            </tbody>
          </table>
        <bulma-paginate :pages="pages" @paginate="changePage"></bulma-paginate>
      </div>
      <div v-else>
          No genres in the database.
      </div>
    </div>

      <delete-form 
        v-if="deleteGenre" 
        :resource="'genre (' + deleteGenre.name +')'" 
        @confirm="deleteGenreSend"
        @close="deleteGenre=null">
      </delete-form>

      <article class="message" v-if="editGenre">
        <div class="message-header">
          <p>Edit Genre</p>
          <button class="delete" aria-label="delete" @click="editGenre=null"></button>
        </div>
        <div class="message-body">
          <genre-form :action='"Edit"' :genre="editGenre" @send="editGenreSend"></genre-form>
        </div>
      </article>

    <hr>
    <article class="message">
      <div class="message-header"><p>Add a Genre</p></div>
      <div class="message-body">
		      <genre-form :action='"Add"' @send="addGenre"></genre-form>
      </div>
    </article>
    
	</div>
</template>

<script>
import NProgress from 'nprogress'
import ErrorHandler from '../../../../utilities/ErrorHandler'
import DeleteForm from '../../../utilities/DeleteForm.vue'
import GenreForm from './Form.vue'


export default {
  components: {DeleteForm, GenreForm},
  data() {
    return {
      genres: [],
      pages: 0,

      deleteGenre: null,
      editGenre: null,
    }
  },
  created(){
    this.getGenres();
  },
  methods: {
      toggleDelete(genre) {
        this.deleteGenre = genre;
        this.editGenre = null;
      },
      toggleEdit(genre) {
        this.editGenre = genre;
        this.deleteGenre = null;
      },
      addGenre(genre) {
        var success = true;
        NProgress.start();
        axios.post('/api/admin/genres', {name: genre.name,  acronym: genre.acronym})
        .catch((error) => { 
          var nameError = error.response.data.errors.name
          if(nameError)
          {
            NProgress.done();
            success = false;
            var errorsReadable = ErrorHandler.array(nameError);
            flash(errorsReadable, 'error')
          }
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              this.genres.push(response.data)
              flash('Genre Added', 'success')
            }
        });
      },
      editGenreSend(editGenre) {
        var success = true;
        NProgress.start();
        axios.put(('/api/admin/genres/' + editGenre.id + '/edit'), {name: editGenre.name, acronym: editGenre.acronym})
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Edit Genre.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Genre Edited Successfully.', 'success')
              this.editGenre = null;
            }
        });
      },
      deleteGenreSend() {
        var success = true;
        NProgress.start();
        axios.delete(('/api/admin/genres/' + this.deleteGenre.id + '/delete'))
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Delete Genre.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Genre Deleted Successfully.', 'success')
              this.deleteGenre = null;
            }
        });
      },
      getGenres(page = 1) {
        axios.get('/api/admin/genres?page=' + page)
        .then((response) => { this.genres = response.data.data; this.pages = response.data.last_page; })
        .catch((error) => console.log("Genres array not updated."));
      },
      changePage(pageNum) { this.getGenres(pageNum) }
  }
}
</script>