<template>
<div>
	
    <div v-if="reviews.length > 0">
        <div class="columns is-multiline" >
            <div class="column is-half-widescreen is-full-tablet" v-for="review in reviews" :key="review.id">
                
                <review-item :review="review" :hideUser="hideUser"></review-item>

            </div>
        </div>
    </div>

    <article class="message is-warning" v-else>
      <div class="message-body">
        No reviews found.
      </div>
    </article>

</div>
</template>

<script>
import ReviewItem from './ReviewItem.vue';

export default {
    props: ['reviews', 'hideUser'],
    components: { ReviewItem }
}
</script>