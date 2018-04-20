<template>
<div>
	<h1 class="title">Trending</h1>

    <div class="columns is-multiline is-mobile is-centered">
      <div class="column is-narrow" v-for="game in trending" style="min-width: 175px; max-width: 196px">
        <router-link :to="{name: 'Game', params: { id: game.id }}">
            <figure class="card image imageFade tooltip is-tooltip-primary item-shadow" :data-tooltip="game.title">
              <div class="text-container">
                <img :src="$store.state.cdnURL + 'games/icons/' + game.icon" :alt="game.title">
                <div class="bottom-left is-hidden-tablet ">{{ game.title }}</div>
              </div>
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