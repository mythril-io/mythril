<template>
<div>
	
      <nav class="level is-mobile">
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Games</p>
            <p class="title">{{ gamesCount }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Users</p>
            <p class="title">{{ usersCount }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Reviews</p>
            <p class="title">{{ reviewsCount }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Recommendations</p>
            <p class="title">{{ recommendationsCount }}</p>
          </div>
        </div>
      </nav>
      
</div>
</template>

<script>
export default {
    props: ['gamesCount', 'usersCount', 'reviewsCount', 'recommendationsCount']
}
</script>