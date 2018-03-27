<template>
  <div>
    <h1 class="title">Platforms</h1>
    <div class="content">
      <blockquote>Platforms currently in Database: <strong>{{ platforms.length }}</strong></blockquote>
    </div>
    <div class="content">
      <div v-if="platforms.length > 0"> 
          <table class="table is-striped">
            <thead>
              <tr>
                <th>Platform</th>
                <th width="10%"></th>
                <th width="10%"></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="platform in platforms">
                <td valign="middle">{{ platform.name }} <span class="tag is-light is-medium" v-if="platform.acronym">({{ platform.acronym }})</span></td>
                <td><a class="button is-primary" @click="toggleEdit(platform)">Edit</a></td>
                <td><a class="button is-danger" @click="toggleDelete(platform)">Delete</a></td>
              </tr>
            </tbody>
          </table>
        <bulma-paginate :pages="pages" @paginate="changePage"></bulma-paginate>
      </div>
      <div v-else>
          No platforms in the database.
      </div>
    </div>

      <delete-form 
        v-if="deletePlatform" 
        :resource="'platform (' + deletePlatform.name +')'" 
        @confirm="deletePlatformSend"
        @close="deletePlatform=null">
      </delete-form>

      <article class="message" v-if="editPlatform">
        <div class="message-header">
          <p>Edit Platform</p>
          <button class="delete" aria-label="delete" @click="editPlatform=null"></button>
        </div>
        <div class="message-body">
          <platform-form :action='"Edit"' :platform="editPlatform" @send="editPlatformSend"></platform-form>
        </div>
      </article>

    <hr>
    <article class="message">
      <div class="message-header"><p>Add a Platform</p></div>
      <div class="message-body">
          <platform-form :action='"Add"' @send="addplatform"></platform-form>
      </div>
    </article>
    
  </div>
</template>

<script>
import NProgress from 'nprogress'
import ErrorHandler from '../../../../utilities/ErrorHandler'
import DeleteForm from '../../../utilities/DeleteForm.vue'
import PlatformForm from './Form.vue'

export default {
  components: {DeleteForm, PlatformForm},
  data() {
    return {
      platforms: [],
      pages: 0,

      deletePlatform: null,
      editPlatform: null,
    }
  },
  created(){
    this.getPlatforms();
  },
  methods: {
      toggleDelete(platform) {
        this.deletePlatform = platform;
        this.editPlatform = null;
      },
      toggleEdit(platform) {
        this.editPlatform = platform;
        this.deletePlatform = null;
      },
      addplatform(platform) {
        var success = true;
        NProgress.start();
        axios.post('/api/admin/platforms', {name: platform.name,  acronym: platform.acronym})
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
              this.platforms.push(response.data)
              flash('platform Added', 'success')
            }
        });
      },
      editPlatformSend(editPlatform) {
        var success = true;
        NProgress.start();
        axios.put(('/api/admin/platforms/' + editPlatform.id + '/edit'), {name: editPlatform.name, acronym: editPlatform.acronym})
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Edit Platform.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Platform Edited Successfully.', 'success')
              this.editPlatform = null;
            }
        });
      },
      deletePlatformSend() {
        var success = true;
        NProgress.start();
        axios.delete(('/api/admin/platforms/' + this.deletePlatform.id + '/delete'))
        .catch((error) => { 
            NProgress.done();
            success = false;
            flash('Unable to Delete Platform.', 'error')
        })
        .then((response) => {
            if(success) {
              NProgress.done();
              flash('Platform Deleted Successfully.', 'success')
              this.deletePlatform = null;
            }
        });
      },
      getPlatforms(page = 1) {
        axios.get('/api/admin/platforms?page=' + page)
        .then((response) => { this.platforms = response.data.data; this.pages = response.data.last_page; })
        .catch((error) => console.log("Platforms array not updated."));
      },
      changePage(pageNum) { this.getPlatforms(pageNum) }
  }
}
</script>