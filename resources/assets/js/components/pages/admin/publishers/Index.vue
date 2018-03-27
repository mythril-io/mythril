<template>
  <div>
    <h1 class="title">Publishers</h1>
    <div class="content">
      <blockquote>Publishers currently in Database: <strong>{{ publishers.length }}</strong></blockquote>
    </div>
      <article class="message">
        <div class="message-header"><p>Select a Publisher</p></div>
        <div class="message-body">

          <div class="field">
            <p class="control">
              <multiselect
                v-model="selectedPublisher"
                :options="publishers"
                :close-on-select="true"
                placeholder="Select a Publisher"
                track-by="name"
                label="name"
                class="select" name="selectedPublisher">
              </multiselect>
            </p>
          </div>

          <div v-if="selectedPublisher">
            <a class="button is-primary" @click="toggleEdit(selectedPublisher)">Edit</a>
            <a class="button is-danger" @click="toggleDelete(selectedPublisher)">Delete</a>
          </div>

        </div>
      </article>

      <delete-form 
        v-if="deletePublisher" 
        :resource="'publisher (' + deletePublisher.name +')'" 
        @confirm="deletePublisherSend"
        @close="deletePublisher=null">
      </delete-form>

      <article class="message" v-if="editPublisher">
        <div class="message-header">
          <p>Edit Publisher</p>
          <button class="delete" aria-label="delete" @click="editPublisher=null"></button>
        </div>
        <div class="message-body">
          <publisher-form :action='"Edit"' :publisher="editPublisher" @send="editPublisherSend"></publisher-form>
        </div>
      </article>

    <hr>
    <article class="message">
      <div class="message-header"><p>Add a Publisher</p></div>
      <div class="message-body">
          <publisher-form :action='"Add"' @send="addPublisher"></publisher-form>
      </div>
    </article>
    
  </div>
</template>

<script>
import NProgress from 'nprogress'
import ErrorHandler from '../../../../utilities/ErrorHandler'
import Multiselect from 'vue-multiselect'
import DeleteForm from '../../../utilities/DeleteForm.vue'
import PublisherForm from './Form.vue'

export default {
  components: {Multiselect, DeleteForm, PublisherForm},
  data() {
    return {
      selectedPublisher: null,
      publishers: [],
      pages: 0,

      deletePublisher: null,
      editPublisher: null,
    }
  },
  created(){
    this.getPublishers();
  },
  methods: {
      toggleDelete(publisher) {
        this.deletePublisher = publisher;
        this.editPublisher = null;
      },
      toggleEdit(publisher) {
        this.editPublisher = publisher;
        this.deletePublisher = null;
      },
      addPublisher(publisher) {
        var success = true;
        NProgress.start();
        axios.post('/api/admin/publishers', {name: publisher.name, country: publisher.country, description: publisher.description})
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
              this.publishers.push(response.data)
              flash('Publisher Added', 'success')
            }
        });
      },
      editPublisherSend(editPublisher) {
        var success = true;
        NProgress.start();
        axios.put(('/api/admin/publishers/' + editPublisher.id + '/edit'), {name: editPublisher.name, country: editPublisher.country, description: editPublisher.description })
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Edit Publisher.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Publisher Edited Successfully.', 'success')
              this.editPublisher = null;
            }
        });
      },
      deletePublisherSend() {
        var success = true;
        NProgress.start();
        axios.delete(('/api/admin/publishers/' + this.deletePublisher.id + '/delete'))
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Delete Publisher.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Publisher Deleted Successfully.', 'success')
              this.deletePublisher = null;
            }
        });
      },
      getPublishers() {
        axios.get('/api/admin/publishers')
        .then((response) => { this.publishers = response.data; })
        .catch((error) => console.log("Publishers array not updated."));
      }
  }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>