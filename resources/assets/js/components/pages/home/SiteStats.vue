<template>
<div>
	
      <nav class="level is-mobile">
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Games</p>
            <p class="title">{{ gamesCount | numberFormat }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Releases</p>
            <p class="title">{{ releasesCount | numberFormat }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Users</p>
            <p class="title">{{ usersCount | numberFormat }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Reviews</p>
            <p class="title">{{ reviewsCount | numberFormat }}</p>
          </div>
        </div>
        <div class="level-item has-text-centered">
          <div>
            <p class="heading">Recommendations</p>
            <p class="title">{{ recommendationsCount | numberFormat }}</p>
          </div>
        </div>
      </nav>
      
</div>
</template>

<script>
var numeral = require('numeral');
export default {
    props: ['gamesCount', 'releasesCount', 'usersCount', 'reviewsCount', 'recommendationsCount']
}
</script>