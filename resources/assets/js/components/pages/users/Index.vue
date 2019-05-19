<template>
  <div>
    <section class="hero is-primary">
      <div class="hero-body">
        <div class="container">
          <h1 class="title has-text-centered-desktop">Users</h1>
          <h2 class="subtitle has-text-centered-desktop">
            <span class="has-text-grey-dark is-size-6">View the profiles of registered users</span>
          </h2>
        </div>
      </div>
    </section>
    <section class="section">
      <div class="container">
        <div v-if="loading" class="columns">
          <div class="column">
            <center>
              <i class="fas fa-spinner fa-spin fa-2x"></i>
            </center>
          </div>
        </div>
        <div v-else-if="users.length > 0">
          <article class="message">
            <div class="message-body">Currently, there are
              <strong>{{ total }}</strong> registered users.
              <strong>
                <router-link :to="{name: 'Register'}">Register Today!</router-link>
              </strong>
            </div>
          </article>

          <user-profile-component :users="users"></user-profile-component>

          <!-- <div class="columns columns is-mobile is-multiline is-centered">
            <div v-for="user in users" class="column is-4-mobile is-2-tablet is-1-desktop" :key="user.id">
              <user-avatar 
                :user="user"
                avatarSize="is-96x96"
              >
              </user-avatar>
            </div>
          </div> -->

          <b-pagination
              :total="total"
              :current.sync="current"
              per-page="24"
              order="is-centered"
              @change="changePage">
          </b-pagination>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import UserProfileComponent from "../components/UserProfileComponent.vue";
import UserAvatar from "../components/UserAvatar.vue";
var moment = require("moment-timezone");

export default {
  props: ["user"],
  components: { UserProfileComponent, UserAvatar },
  data() {
    return {
      users: [],
      current: 1,

      total: 0,
      pages: 0,
      loading: true
    };
  },
  methods: {
    getUsers(page = 1) {
      axios
        .get("/api/users?page=" + page)
        .then(response => {
          if (response.data.data.length == 0) {
            this.$router.push({ name: "Users" });
          }
          this.users = response.data.data;
          this.total = response.data.total;
          this.pages = response.data.last_page;
          this.loading = false;
        })
        .catch(error => console.log("Users array not updated."));
    },
    changePage(pageNum) {
      this.$router.push({ name: "Users", query: { page: pageNum } });
    }
  },
  created() {
    if (this.$route.query.page) {
      var page = Number(this.$route.query.page);
    } else {
      var page = 1;
    }

    this.current = page;
    this.getUsers(page);
  }
};
</script>