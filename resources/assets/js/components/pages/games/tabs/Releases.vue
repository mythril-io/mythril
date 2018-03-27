<template>
	<div>
		<div class="content">
			<h2>Releases</h2>
		</div>

			        <table class="table is-fullwidth is-bordered is-striped is-hoverable" style="font-size: 12px;">
			          <thead>
			            <tr>
			              <th>Alternate Title</th>
			              <th>Platform</th>
			              <th>Publisher</th>
			              <th>Co-Developer</th>
			              <th>North America</th>
			              <th>Japan</th>
			              <th>Europe</th>
			              <th>Worldwide</th>
			            </tr>
			          </thead>
			          <tbody>
			            <tr v-for="release in game.releases">
			              <td>{{ release['alternate_title'] ? release['alternate_title'] : "N/A" }}</td>
			              <td>{{ release['platform']['name'] }}</td>
			              <td>{{ release['publisher']['name'] }}</td>
			              <td>{{ release['codeveloper'] ? release['codeveloper']['name'] : "N/A"  }}</td>
			              <td>{{ release['NA'] | dateFormat }}</td>
			              <td>{{ release['JP'] | dateFormat }}</td>
			              <td>{{ release['EU'] | dateFormat }}</td>
			              <td>{{ release['WW'] | dateFormat }}</td>
			            </tr>
			          </tbody>
			        </table>

	</div>
</template>

<script>
import moment from 'moment';

export default {
    props: ['game'],
	filters: {
	  dateFormat(date) {
	    if(date)
	    {
	      return moment.utc(date).format("MMM Do YYYY");;
	    }
	    else
	    {
	      return "N/A";
	    }
	  }
	},
	methods: {
      	addToLibrary(release) {

      	},
    }
}
</script>