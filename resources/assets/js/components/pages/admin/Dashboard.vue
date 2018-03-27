<template>
	<div>
		<h1 class="title">Dashboard</h1>

		<div class="tile is-ancestor">
		  <div class="tile is-vertical is-8">
		    <div class="tile">
		      <div class="tile is-parent is-vertical">
		        <article class="tile is-child notification is-primary">
		          <p class="title">Games</p>
		          <p class="subtitle">Recently Added</p>
			        <div class="content">
			        	<ol>
			        		<li>Final Fantasy VII</li>
			        		<li>Kingdom Hearts</li>
			        		<li>Persona 5</li>
			        		<li>Catherine: Full Body</li>
			        	</ol>
			        </div>
		        </article>
		        <article class="tile is-child notification is-warning">
		          <p class="title">Members</p>
		          <p class="subtitle">Recently Registered</p>
			        <div class="content">
			        	<div class="tags">
						  <span class="tag">Tifa</span>
						  <span class="tag">Balthier</span>
						  <span class="tag">Balthier</span>
						  <span class="tag">Sephiroth</span>
						</div>
			        </div>
		        </article>
		      </div>
		      <div class="tile is-parent">
		        <article class="tile is-child notification is-info">
		          <p class="title">Statistics</p>
		          <p class="subtitle">Database</p>
			        <div class="content">
			        	<ul>
			        		<li>Games: 200</li>
			        		<li>Members: 100</li>
			        		<li>Reviews: 25</li>
			        		<li>Recommendations: 30</li>
			        		<hr>
			        		<li>Franchises: 20</li>
			        		<li>Developers: 150</li>
			        		<li>Publishers: 75</li>
			        		<li>Genres: 15</li>
			        		<li>Platforms: 30</li>
			        	</ul>
			        </div>
		        </article>
		      </div>
		    </div>
		    <div class="tile is-parent">
		      <article class="tile is-child notification is-danger">
		        <p class="title">Wide tile</p>
		        <p class="subtitle">Aligned with the right tile</p>
		        <div class="content">
		          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.
		          Suspendisse varius ligula in molestie lacinia. Maecenas varius eget ligula a sagittis. Pellentesque interdum, nisl nec interdum maximus, augue diam porttitor lorem, et sollicitudin felis neque sit amet erat. 
		        </div>
		      </article>
		    </div>
		  </div>
		  <div class="tile is-parent">
		    <article class="tile is-child notification is-success">
		      <div class="content">
		        <p class="title">Tall tile</p>
		        <p class="subtitle">With even more content</p>
		        <div class="content">
		          
		          <figure class="image">
		            <img src="/images/img2.png" class="imageFade">
		          </figure>

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam semper diam at erat pulvinar, at pulvinar felis blandit. Vestibulum volutpat tellus diam, consequat gravida libero rhoncus ut. Morbi maximus, leo sit amet vehicula eleifend, nunc dui porta orci, quis semper odio felis ut quam.

		        </div>
		      </div>
		    </article>
		  </div>
		</div>

	</div>
</template>

<script>
	export default {
		
	}
</script>