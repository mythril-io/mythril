<template>
<div>
	
    <div v-if="reviews.length > 0">
        <div class="columns is-multiline" >
            <div class="column is-half-widescreen is-full-tablet" v-for="review in reviews" :key="review.id">
                
                <review-item 
                    :review="review" 
                    :hideUser="hideUser"
                    :showUserAvatar="showUserAvatar"
                ></review-item>

            </div>
        </div>
    </div>

    <article class="message is-warning" v-else>
      <div class="message-body">
        <span v-if="customMessage">
            {{ customMessage }}
        </span>
        <span v-else>No reviews found. Be the first to write one! <router-link :to="{name: 'CreateReview'}">Write a Review</router-link></span>
      </div>
    </article>

</div>
</template>

<script>
import ReviewItem from './ReviewItem.vue';

export default {
    props: ['reviews', 'hideUser', 'showUserAvatar', 'customMessage'],
    components: { ReviewItem }
}
</script>