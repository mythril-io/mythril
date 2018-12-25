<template>
  <div>
    <h4>Users</h4>
    <div class="field has-addons is-marginless">
      <p class="control">
        <a class="button stat-count">{{ libraryCount.playing }}</a>
      </p>
      <p class="control">
        <a class="button is-primary stat-label">Playing</a>
      </p>
    </div>
    <div class="field has-addons is-marginless">
      <p class="control">
        <a class="button stat-count">{{ libraryCount.planToPlay }}</a>
      </p>
      <p class="control">
        <a class="button is-info stat-label">Plan To Play</a>
      </p>
    </div>
    <div class="field has-addons is-marginless">
      <p class="control">
        <a class="button stat-count">{{ libraryCount.completed }}</a>
      </p>
      <p class="control">
        <a class="button is-success stat-label">Completed</a>
      </p>
    </div>
    <div class="field has-addons is-marginless">
      <p class="control">
        <a class="button stat-count">{{ libraryCount.onHold }}</a>
      </p>
      <p class="control">
        <a class="button is-warning stat-label">On Hold</a>
      </p>
    </div>
    <div class="field has-addons is-marginless">
      <p class="control">
        <a class="button stat-count">{{ libraryCount.dropped }}</a>
      </p>
      <p class="control">
        <a class="button is-danger stat-label">Dropped</a>
      </p>
    </div>
  </div>
</template>

<script>
export default {
  props: ["game"],
  data() {
    return {
      libraryCount: {
        playing: 0,
        planToPlay: 0,
        completed: 0,
        onHold: 0,
        dropped: 0
      }
    };
  },
  methods: {
    getUserLibraries() {
      axios
        .get("/api/library/games/" + this.$route.params.id)
        .then(response => {
          this.libraryCount = response.data;
        })
        .catch(error => "");
    }
  },
  created() {
    this.getUserLibraries();
  }
};
</script>