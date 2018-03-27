<template>
<div>
	
    <div v-if="reviews.length > 0">
        <div class="columns is-multiline" >
            <div class="column is-half-widescreen is-full-tablet" v-for="review in reviews" :key="review.id">
                
                <review-item :review="review" :hideUser="hideUser"></review-item>

            </div>
        </div>
    </div>

    <div class="notification is-warning" v-else>
        No reviews found.
    </div>

</div>
</template>

<script>
import ReviewItem from './ReviewItem.vue';

export default {
    props: ['reviews', 'hideUser'],
    components: { ReviewItem }
}
</script>