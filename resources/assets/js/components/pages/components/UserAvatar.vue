<template>
<div class="card">
    <div class="card-image imageFade">
        <router-link :to="{name: 'User', params: { id: user.id }}">
        <figure class="image is-square username-hover" v-if="user.avatar">
            <img
            v-lazy="$store.state.userAvatarURL + user.avatar"
            style="object-fit: cover;"
            :title="user.username"
            >
            <div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
        </figure>

        <figure class="image is-square username-hover" v-else>
            <img
            v-lazy="$store.state.cdnURL + 'users/avatars/default.jpg'"
            style="object-fit: cover;"
            :title="user.username"
            >
            <div class="username-overlay"><span class="username-text">{{ user.username }}</span></div>
        </figure>
        </router-link>
    </div>
</div>

</template>

<script>
export default {
    props: [ 'user' ]
}
</script>