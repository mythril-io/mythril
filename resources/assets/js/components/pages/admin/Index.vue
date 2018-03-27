<template lang="html">
<div>

	<section class="section">
		<div class="container">
		<div class="notification is-primary">
			<h1 class="title">Admin Panel</h1>
		</div>
		<div class="columns">
			<div class="column is-one-fifth">
				<aside class="menu box">
				  <p class="menu-label">
				    General
				  </p>
				  <ul class="menu-list">
				    <li><router-link :to="{name: 'Admin'}" exact>Dashboard</router-link></li>
				  </ul>
				  <p class="menu-label">
				    Games
				  </p>
				  <ul class="menu-list">
				    <li><router-link :to="{name: 'AdminGame'}">Add a Game</router-link></li>
				    <li><router-link :to="{name: 'AdminGameEdit'}">Edit/Delete a Game</router-link></li>
				    <li><router-link :to="{name: 'AdminReleaseAdd'}">Add Releases</router-link></li>
				    <li><router-link :to="{name: 'AdminReleaseEdit'}">Edit/Delete Releases</router-link></li>
				    <!-- <li><a>Search/List</a></li> -->
				  </ul>
				  <p class="menu-label">
				    Games Franchises
				  </p>
				  <ul class="menu-list">
				    <li><a>Add a Franchise</a></li>
				    <li><a>Edit/Delete a Franchise</a></li>
				    <li><a>Attach/Detach Games</a></li>
				    <!-- <li><a>Search/List</a></li> -->
				  </ul>
				  <p class="menu-label">
				    Etcetera
				  </p>
				  <ul class="menu-list">
				    <li><router-link :to="{name: 'AdminDeveloper'}">Developers</router-link></li>
				    <li><router-link :to="{name: 'AdminPublisher'}">Publishers</router-link></li>
				    <li><router-link :to="{name: 'AdminGenre'}">Genres</router-link></li>
				    <li><router-link :to="{name: 'AdminPlatform'}">Platforms</router-link></li>
				  </ul>
				  <p class="menu-label">
				    Members
				  </p>
				  <ul class="menu-list">
				    <li><a>Search/List</a></li>
				  </ul>
				</aside>
			</div>
			<div class="column">

				<router-view></router-view>

			</div>
		</div>
	</div>
	</section>

</div>
</template>

<script>
export default {
}
</script>
