<template>
	<nav class="level">
		<div class="level-item">
			<div>
				<h1 class="title">Top Rated Games</h1>
				<h2 class="subtitle content">
				  <ol>
				    <li v-for="game in ranked">
				    	<router-link :to="{name: 'Game', params: { id: game.id }}">{{ game.title }}</router-link>
			    	</li>
				  </ol>
				</h2>
			</div>
		</div>
		<div class="level-item">
			<div>
				<h1 class="title">Most Popular Games</h1>
				<h2 class="subtitle content">
				  <ol>
				    <li v-for="game in popular">
				    	<router-link :to="{name: 'Game', params: { id: game.id }}">{{ game.title }}</router-link>
				    </li>
				  </ol>
				</h2>
			</div>
		</div>
	</nav>
</template>

<script>
export default {
    props: ['ranked', 'popular']
}
</script>