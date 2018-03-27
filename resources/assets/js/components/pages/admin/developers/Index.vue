<template>
  <div>
    <h1 class="title">Developers</h1>
    <div class="content">
      <blockquote>Developers currently in Database: <strong>{{ developers.length }}</strong></blockquote>
    </div>
      <article class="message">
        <div class="message-header"><p>Select a Developer</p></div>
        <div class="message-body">

          <div class="field">
            <p class="control">
              <multiselect
                v-model="selectedDeveloper"
                :options="developers"
                :close-on-select="true"
                placeholder="Select a Developer"
                track-by="name"
                label="name"
                class="select" name="selectedDeveloper">
              </multiselect>
            </p>
          </div>

          <div v-if="selectedDeveloper">
            <a class="button is-primary" @click="toggleEdit(selectedDeveloper)">Edit</a>
            <a class="button is-danger" @click="toggleDelete(selectedDeveloper)">Delete</a>
          </div>

        </div>
      </article>

      <delete-form 
        v-if="deleteDeveloper" 
        :resource="'developer (' + deleteDeveloper.name +')'" 
        @confirm="deleteDeveloperSend"
        @close="deleteDeveloper=null">
      </delete-form>

      <article class="message" v-if="editDeveloper">
        <div class="message-header">
          <p>Edit Developer</p>
          <button class="delete" aria-label="delete" @click="editDeveloper=null"></button>
        </div>
        <div class="message-body">
          <developer-form :action='"Edit"' :developer="editDeveloper" @send="editDeveloperSend"></developer-form>
        </div>
      </article>

    <hr>
    <article class="message">
      <div class="message-header"><p>Add a Developer</p></div>
      <div class="message-body">
          <developer-form :action='"Add"' @send="addDeveloper"></developer-form>
      </div>
    </article>
    
  </div>
</template>

<script>
import NProgress from 'nprogress'
import ErrorHandler from '../../../../utilities/ErrorHandler'
import Multiselect from 'vue-multiselect'
import DeleteForm from '../../../utilities/DeleteForm.vue'
import DeveloperForm from './Form.vue'

export default {
  components: {Multiselect, DeleteForm, DeveloperForm},
  data() {
    return {
      selectedDeveloper: null,
      developers: [],
      pages: 0,

      deleteDeveloper: null,
      editDeveloper: null,
    }
  },
  created(){
    this.getDevelopers();
  },
  methods: {
      toggleDelete(developer) {
        this.deleteDeveloper = developer;
        this.editDeveloper = null;
      },
      toggleEdit(developer) {
        this.editDeveloper = developer;
        this.deleteDeveloper = null;
      },
      addDeveloper(developer) {
        var success = true;
        NProgress.start();
        axios.post('/api/admin/developers', {name: developer.name, country: developer.country, description: developer.description})
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
              this.developers.push(response.data)
              flash('Developer Added', 'success')
            }
        });
      },
      editDeveloperSend(editDeveloper) {
        var success = true;
        NProgress.start();
        axios.put(('/api/admin/developers/' + editDeveloper.id + '/edit'), {name: editDeveloper.name, country: editDeveloper.country, description: editDeveloper.description })
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Edit Developer.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Developer Edited Successfully.', 'success')
              this.editDeveloper = null;
            }
        });
      },
      deleteDeveloperSend() {
        var success = true;
        NProgress.start();
        axios.delete(('/api/admin/developers/' + this.deleteDeveloper.id + '/delete'))
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Delete Developer.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Developer Deleted Successfully.', 'success')
              this.deleteDeveloper = null;
            }
        });
      },
      getDevelopers() {
        axios.get('/api/admin/developers')
        .then((response) => { this.developers = response.data; })
        .catch((error) => console.log("Developers array not updated."));
      }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>