<template>
<div>
<!--  	<div class="content">
        <h2>Stats</h2>
    </div> -->

    
    <div class="columns is-multiline is-variable is-8">
    	<!-- Pie Chart for library item status' ie. Completed, Playing etc -->
    	<div class="column is-4">
    		<h2 class="title">Play Statistics</h2>
    		<pie-chart 
    			:chart-data="categorizedLibraryCount" 
    			:options="{maintainAspectRatio: false, tooltips: tooltips}">
			</pie-chart>
    	</div>

    	<!-- Doughnut Chart showing number of games that are physical and digital. Next to it, number of games -->
    	<div class="column is-4">
    		<h2 class="title">Physical vs. Digital</h2>
    		<doughnut-chart 
    			:chart-data="digitalPhysicalCount" 
    			:options="{maintainAspectRatio: false, tooltips: tooltips}">
			</doughnut-chart>
    	</div>

    	 <!-- Pie Chart of genres (taken from libraries) -->
    	<div class="column is-4">
    		<h2 class="title">Favoured Genres</h2>
    		<doughnut-chart 
    			:chart-data="favouredGenres" 
    			:options="{maintainAspectRatio: false, tooltips: tooltips}">
			</doughnut-chart>
    	</div>

		<!-- Bar graph for number of reviews, recommendations -->
<!--     	<div class="column is-4">
    		<h2 class="title">Contribution</h2>
    		<bar-chart :chart-data="userContributions" :options="contributionBarOptions"></bar-chart>
    	</div> -->

		<!-- Bar graph for score distribution -->
    	<div class="column is-6">
    		<h2 class="title">Score Distribution</h2>
    		<bar-chart :chart-data="scoreDistribution" :options="scoreBarOptions" ></bar-chart>
    	</div>

    	<div class="column is-6">
    		<h2 class="title">Misc.</h2>
			<table class="table is-hoverable is-fullwidth is-striped">
				<thead>
				  <tr>
				    <th></th>
				    <th></th>
				  </tr>
				</thead>
				<tbody>
				  <tr>
				    <th>
						<b-tooltip label="Completion % = Completed / All Play States" multilined>
						<span>Completion Percentage</span>
						  <b-icon
							pack="fas"
							icon="info-circle"
							size="is-small"
							type="is-info" custom-class="icon-margin">
							</b-icon>
						</b-tooltip>
				    </th>
				    <td>{{ completionPercentage }}</td>
				  </tr>
				  <tr>
				    <th>Average Game Score</th>
				    <td>{{ meanGameScorePercentage }}</td>
				  </tr>
				  <tr>
				    <th>Games Owned/Total Games</th>
				    <td>{{ gamesOwned }}/{{ gamesTotal }} {{ gamesOwnedPercentage ? ' (' + gamesOwnedPercentage + ')' : '' }}</td>
				  </tr>
<!-- 				  <tr>
				    <th>Percentage of Games Owned</th>
				    <td>{{ gamesOwnedPercentage }}</td>
				  </tr> -->
				  <tr>
				    <th>Reviews</th>
				    <td>{{ this.displayedUser.reviews_count }}</td>
				  </tr>
				  <tr>
				    <th>Recommendations</th>
				    <td>{{ this.displayedUser.recommendations_count }}</td>
				  </tr>
				  <tr>
				    <th>Followers</th>
				    <td>{{ this.displayedUser.followers_count }}</td>
				  </tr>
				  <tr>
				    <th>Following</th>
				    <td>{{ this.displayedUser.following_count }}</td>
				  </tr>
				</tbody>
			</table>
    	</div>


    </div>

    <!-- Completion Percentage (completed/totalLibraryEntries), Number of Owned Games, Owned Percentage (owned/totalLibraryEntries), Reviews, Recommendations,  -->

</div>
</template>

<script>
import PieChart from '../../../utilities/charts/Pie.js'
import DoughnutChart from '../../../utilities/charts/Doughnut.js'
import BarChart from '../../../utilities/charts/Bar.js'

export default {
	props: ['displayedUser'],
  	components:{ PieChart, DoughnutChart, BarChart },
    data() {
    	return {
    		userLibrary: [],
    		contributionBarOptions: {
    			scales: { yAxes: [{ ticks: {beginAtZero:true}, scaleLabel: {display: true, labelString: 'Amount'} }] },
    			legend: { display: false },
    			maintainAspectRatio: false,
			},
    		scoreBarOptions: {
    			scales: { 
    				yAxes: [{ ticks: {beginAtZero:true}, scaleLabel: {display: true, labelString: 'Number of Games'} }], 
    				xAxes: [{ scaleLabel: {display: true, labelString: 'Score (%)'} }] 
    			},
    			legend: { display: false },
    			maintainAspectRatio: false,
			},
	        tooltips: {
	            callbacks: {
	                label: function(tooltipItem, data) {
	                    var allData = data.datasets[tooltipItem.datasetIndex].data;
	                    var tooltipLabel = data.labels[tooltipItem.index];
	                    var tooltipData = allData[tooltipItem.index];
	                    var total = 0;
	                    for (var i in allData) {
	                        total += allData[i];
	                    }
	                    var tooltipPercentage = Math.round((tooltipData / total) * 100);
	                    return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
	                }
	            }
	        }
    	}
    },
	computed: {
		completionPercentage() {
			var completedCount = _.filter(this.displayedUser.libraries, ['play_status_id', 3]).length
			var total = this.displayedUser.libraries.length //Subtract continuous and dropped??
			var completionPercentage = (completedCount/total)*100

			var roundedPercentage = parseFloat(completionPercentage).toFixed(2);
			if(isNaN(roundedPercentage)) {return 'N/A' }
			return roundedPercentage + '%'
		},
		gamesTotal() {
			return this.displayedUser.libraries.length
		},
		gamesOwned() {
			return _.filter(this.displayedUser.libraries, ['own', 1]).length
		},
		gamesOwnedPercentage() {
			var ownedPercentage = (this.gamesOwned/this.gamesTotal)*100
			var roundedPercentage = parseFloat(ownedPercentage).toFixed(2);

			if(isNaN(roundedPercentage)) {return null }
			return roundedPercentage + '%'
		},
		meanGameScorePercentage() {
			var libraries = _.filter(this.displayedUser.libraries, function(o) { return (o.score != null); })
			var meanScore = _.meanBy(libraries, 'score');
			var meanPercentage = (meanScore/10)*100
			var roundedMean = parseFloat(meanPercentage).toFixed(2);

			if(isNaN(roundedMean)) {return 'N/A' }
			return roundedMean + '%'
		},
		categorizedLibraryCount() {
			return {
			    datasets: [{
			        data: [
			        	_.filter(this.displayedUser.libraries, ['play_status_id', 1]).length,
			        	_.filter(this.displayedUser.libraries, ['play_status_id', 2]).length,
			        	_.filter(this.displayedUser.libraries, ['play_status_id', 3]).length,
			        	_.filter(this.displayedUser.libraries, ['play_status_id', 4]).length,
			        	_.filter(this.displayedUser.libraries, ['play_status_id', 5]).length,
			        	_.filter(this.displayedUser.libraries, ['play_status_id', 6]).length,
			        ],
			        backgroundColor: [
			            'hsl(171, 100%, 41%)',
			            'hsl(217, 71%, 53%)',
			            'hsl(141, 71%, 48%)',
			            'hsl(48, 100%, 67%)',
			            'hsl(348, 100%, 61%)',
			            'hsl(0, 0%, 96%)']
			    }],
			    labels: ['Currently Playing', 'Plan to Play', 'Completed', 'On Hold', 'Dropped', 'Continuously Playing']
			}
		},
		digitalPhysicalCount() {
			var filteredOwn = _.filter(this.displayedUser.libraries, ['own', 1])
			return {
			    datasets: [{
			        data: [
			        	_.filter(filteredOwn, ['digital', 0]).length,
			        	_.filter(filteredOwn, ['digital', 1]).length
			        ],
			        backgroundColor: [
			            'hsl(171, 100%, 41%)',
			            'hsl(348, 100%, 61%)']
			    }],
			    labels: ['Physical', 'Digital']
			}
		},
		userContributions() {
			return {
				label: 'fgh',
			    datasets: [{
			        data: [
			        	this.displayedUser.reviews_count,
			        	this.displayedUser.recommendations_count
			        ],
			        backgroundColor: [
			            'hsl(171, 100%, 41%)',
			            'hsl(48, 100%, 67%)']
			    }],
			    labels: ['Reviews', 'Recommendations']
			}
		},
		scoreDistribution() {
			var libraries = _.filter(this.displayedUser.libraries, function(o) { return (o.score != null); })
			return {
				label: 'fgh',
			    datasets: [{
			        data: [
			        	_.filter(libraries, function(o) { return (o.score >= 0 && o.score < 1); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 1 && o.score < 2); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 2 && o.score < 3); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 3 && o.score < 4); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 4 && o.score < 5); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 5 && o.score < 6); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 6 && o.score < 7); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 7 && o.score < 8); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 8 && o.score < 9); }).length,
			        	_.filter(libraries, function(o) { return (o.score >= 9 && o.score < 10); }).length,
			        	_.filter(libraries, function(o) { return (o.score == 10); }).length
			        ],
			        backgroundColor: 'hsl(0, 0%, 86%)'
			    }],
			    labels: ['0', '10', '20', '30', '40', '50', '60', '70', '80', '90', '100']
			}
		},
		favouredGenres() {
			//All Genres
			var genres = []

			_.forEach(this.displayedUser.libraries, function(entry) {
				_.forEach(entry.game.genres, function(o) {
				  genres.push({'id': o.id, 'name': o.name})
				});
			});

			var genreCount= _.countBy(genres, 'name')

			var genreData = Object.values(genreCount)
			var genreLabels = Object.keys(genreCount)

			var sum = _.sum(genreData)
			var genrePercentData = []
				_.forEach(genreData, function(o) {
					var percent = (o/sum)*100
					var roundedPercent = parseFloat(percent).toFixed(2);
				  	genrePercentData.push(roundedPercent)
				});

			return {
			    datasets: [{
			        data: genreData,
			        backgroundColor: [
			        	'hsl(171, 100%, 41%)',
			            'hsl(217, 71%, 53%)',
			            'hsl(141, 71%, 48%)',
			            'hsl(48, 100%, 67%)',
			            'hsl(348, 100%, 61%)',
			            'hsl(0, 0%, 96%)']
			    }],
			    labels: genreLabels
			}
		},
	}
}
</script>