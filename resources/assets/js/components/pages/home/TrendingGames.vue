<template>
<div>
	<h1 class="title">Trending</h1>

    <div class="columns is-multiline is-mobile is-centered">
      <div class="column is-one-third-mobile is-one-quarter-tablet is-2-desktop" v-for="game in trending">
        <router-link :to="{name: 'Game', params: { id: game.id }}">
            <figure class="card image imageFade tooltip is-tooltip-primary" :data-tooltip="game.title">
              <img :src="$store.state.cdnURL + 'games/icons/' + game.icon" :alt="game.title">
            </figure>
        </router-link>
      </div>
    </div>
    
</div>
</template>

<script>
export default {
    props: ['trending']
}
</script>