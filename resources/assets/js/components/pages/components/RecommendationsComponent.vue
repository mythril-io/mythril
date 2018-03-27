<template>
<div>
	
    <div v-if="recommendations.length > 0">
        <div class="columns is-multiline" >
            <div class="column is-half-desktop is-full-tablet" v-for="recommendation in recommendations">

              <recommendation-item :recommendation="recommendation"></recommendation-item>

            </div>
        </div>
    </div>

    <div class="notification is-warning" v-else>
        No recommendations found.
    </div>

</div>
</template>

<script>
import RecommendationItem from './RecommendationItem.vue';

export default {
    props: ['recommendations', 'hideUser'],
    components: { RecommendationItem }
}
</script>