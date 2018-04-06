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
			              <th>Region</th>
			              <th>Date</th>
			            </tr>
			          </thead>
			          <tbody>
			            <tr v-for="release in sortedReleases">
			              <td>{{ release['alternate_title'] ? release['alternate_title'] : "-" }}</td>
			              <td>{{ release['platform']['name'] }}</td>
			              <td>{{ release['publisher']['name'] }}</td>
			              <td>{{ release['codeveloper'] ? release['codeveloper']['name'] : "-"  }}</td>
			              <td>{{ release['region'] ? release['region']['name'] : "-" }}</td>
			              <td>{{ release['date'] }}</td>
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
	      return "-";
	    }
	  }
	},
  	computed: {
	    sortedReleases() {
	      return _.orderBy(this.game.releases, 'platform.name', 'asc');
	  	}
    },
	methods: {
      	addToLibrary(release) {

      	},
    }
}
</script>