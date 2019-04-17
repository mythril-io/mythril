<template>
  <div>
    <h1 class="title">Trending</h1>

    <div class="columns is-multiline is-mobile is-centered">
      <div class="column" v-for="game in trending" style="min-width: 150px; max-width: 228px">
        <router-link :to="{name: 'Game', params: { id: game.id }}">
          <b-tooltip :label="game.title">
            <figure class="card image">
              <div class="text-container">
                <img v-lazy="$store.state.cdnURL + 'games/icons/' + game.icon" :alt="game.title">
                <div class="bottom-left is-hidden-tablet">{{ game.title }}</div>
              </div>
            </figure>
          </b-tooltip>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["trending"]
};
</script>