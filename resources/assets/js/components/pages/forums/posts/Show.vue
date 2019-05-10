<template>
<div>
    <div v-for="post in posts" :key="post.id">
        <div class="columns">
            <div class="column is-narrow">
                <b-tooltip
                    :label="post.user.username"
                    position="is-top"
                    type="is-dark"
                    animated> 
                    <router-link 
                        tag="figure" 
                        :to="{name: 'User', params: { id: post.user.id }}" 
                        class="image card is-64x64"
                        :style="'background-size: cover; cursor:pointer; background-image: url('+ $store.state.userAvatarURL + post.user.avatar +');'"
                        >
                    </router-link>
                </b-tooltip>
            </div>
            <div class="column">
                <div class="content">
                <div style="margin-bottom: 7px;"><strong>{{ post.user.username }}</strong> <small>{{ post.created_at | ago($store.user)  }}</small></div>
                    <b-collapse
                            
                            :open="false">
                        <!-- <div
                            slot="trigger"
                            class="panel-heading"
                            role="button">
                            <div class="columns is-variable is-1 is-vcentered">
                                <div class="column is-narrow">
                                    <div class="image card is-32x32">
                                        <img src="http://oi64.tinypic.com/2yla7tx.jpg">
                                    </div>
                                </div>
                                <div class="column">
                                    Jane Doe 2 days ago
                                </div>
                            </div>
                        </div> -->
                        <button class="button is-light" slot="trigger" style="margin-bottom: 7px;">
                            <b-icon icon="reply" pack="fas" size="is-small"></b-icon>
                            <span>Balthier</span>
                        </button>

                        <div class="notification" style="margin-bottom: 7px;">
                            <!-- <div class="notification is-white" style="margin: 0 15px 20px 15px;">
                                <div class="columns is-variable is-2">
                                    <div class="column is-narrow">
                                        <div class="image card is-48x48">
                                            <img src="https://mythril.nyc3.cdn.digitaloceanspaces.com/users/avatars/52.png">
                                        </div>
                                    </div>
                                    <div class="column">
                                        <b>Ashe</b><small> 2 days ago</small><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br/>Nulla accumsan, metus ultrices eleifend gravida, nulla nunc varius lectus, nec rutrum justo nibh eu lectus. <br/>Ut vulputate semper dui. Fusce erat odio, sollicitudin vel erat vel, interdum mattis neque.
                                    </div>
                                    <div class="column is-narrow">
                                        <a class="button is-small is-light">
                                            <span class="icon is-small">
                                            <i class="fas fa-chevron-up"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div> -->
                            <div class="columns is-variable is-2">
                                <div class="column is-narrow">
                                    <div class="image card is-48x48">
                                        <img src="http://oi64.tinypic.com/2yla7tx.jpg">
                                    </div>
                                </div>
                                <div class="column">
                                        <b>Balthier</b><small> 2 days ago</small><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit. <br/>Nulla accumsan, metus ultrices eleifend gravida, nulla nunc varius lectus, nec rutrum justo nibh eu lectus. <br/>Ut vulputate semper dui. Fusce erat odio, sollicitudin vel erat vel, interdum mattis neque.

                                </div>
                                <div class="column is-narrow">
                                    <a class="button is-small">
                                        <span class="icon is-small">
                                        <i class="fas fa-chevron-up"></i>
                                        </span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </b-collapse>

        <!-- <b-dropdown hoverable aria-role="list">
            <button class="button is-light" slot="trigger">
                <b-icon icon="reply" size="is-small"></b-icon>
                <span>Balthier</span>
            </button>

            <b-dropdown-item aria-role="menu-item" custom>
                <div style="width:800px;">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga libero praesentium, reprehenderit vero corrupti quisquam illum. Officia quos veritatis sint magnam consectetur qui ipsam, hic nobis modi! Nemo, corporis veniam.
                </div>
            </b-dropdown-item>
        </b-dropdown> -->

                    <p>{{ post.body }}</p>
                </div>
                <!-- Main container -->
                <nav class="level is-mobile">
                <!-- Left side -->
                <div class="level-left">
                </div>

                <!-- Right side -->
                <div class="level-right">
                    <p class="level-item">
                    <a>
                        <span class="icon is-small " style="margin-top: 7px;">
                        <i class="far fa-heart"></i>
                        </span>
                    </a>
                    </p>
                    <p class="level-item">
                    <a>
                        <span class="icon is-small " style="margin-top: 7px;">
                        <i class="fas fa-reply"></i>
                        </span>
                    </a>
                    </p>
                    <p class="level-item">
                    <a>
                        <span class="icon is-small " style="margin-top: 7px;">
                        <i class="fas fa-link"></i>
                        </span>
                    </a>
                    </p>
                </div>
                </nav>
            </div>
        </div>
        <hr>
    </div> 
</div>
</template>

<script>
export default {
  props: ["posts"],
  data() {
    return {
    };
  },
  methods: {
  },
  created() {
  }
};
</script>