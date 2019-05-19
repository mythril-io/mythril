<template>
    <b-tooltip
        :label="getTooltip"
        position="is-top"
        type="is-dark"
        animated> 
        <router-link 
            :to="{name: 'User', params: { id: user.id }}" 
            :class="'image imageFade card ' + avatarSize"
            :style="styleObject"
            >
        </router-link>
    </b-tooltip>
<!-- <div>

    <b-dropdown position="is-bottom-right">
        <a slot="trigger">
            <figure 
                class="image card is-64x64"
                :style="'background-size: cover; cursor:pointer; background-image: url('+ $store.state.userAvatarURL + user.avatar +');'"
            >
            </figure>

        </a>
        <b-dropdown-item custom style="width:300px;">
            <div class="field is-grouped">
                <p class="control">
                    <router-link 
                        tag="a" 
                        :to="{name: 'User', params: { id: user.id }}" 
                        class="button is-primary"
                    >View Profile
                    </router-link>
                </p>
                <p class="control">
                    <a class="button">
                    Follow
                    </a>
                </p>
            </div>
        </b-dropdown-item>
    </b-dropdown>

</div> -->
</template>

<script>
export default {
    props: {
        user: {
          type: Object,
          default: null
        },
        avatarSize: {
          type: String,
          default: ''
        },
        dimension: {
          type: String,
          default: ''
        },
        tooltip: {
          type: String,
          default: ''
        }
    },
    computed: {
        styleObject: function () {
            return {
                backgroundSize: 'cover',
                cursor: 'pointer',
                backgroundImage: 'url('+ this.avatar +')',
                width: this.dimension,
                height: this.dimension
            }
        },
        getTooltip() {
            return this.tooltip ? this.tooltip : this.user.username;
        },
        avatar: function () {
            return this.user.avatar ? this.$store.state.userAvatarURL + this.user.avatar : this.$store.state.userAvatarURL + 'default.jpg';
        }
    }
}
</script>