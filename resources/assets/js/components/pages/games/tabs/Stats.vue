<template>
<div>

    <div class="columns is-multiline is-variable is-8">
    	<!-- Pie Chart for library item status' ie. Completed, Playing etc -->
    	<div class="column is-6">
    		<h2 class="title">Play Statistics</h2>
    		<pie-chart 
    			:chart-data="categorizedLibraryCount" 
    			:options="{maintainAspectRatio: false, tooltips: tooltips}">
			</pie-chart>
    	</div>

    	<!-- Doughnut Chart showing number of games that are physical and digital. Next to it, number of games -->
    	<div class="column is-6">
    		<h2 class="title">Physical vs. Digital</h2>
    		<doughnut-chart 
    			:chart-data="digitalPhysicalCount" 
    			:options="{maintainAspectRatio: false, tooltips: tooltips}">
			</doughnut-chart>
    	</div>

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
				    	<span>Completion Percentage</span>
						<span class="icon has-text-info tooltip is-tooltip-multiline" data-tooltip="Completion % = Completed / All Play States">
						  <i class="fas fa-info-circle"></i>
						</span>
				    </th>
				    <td>{{ completionPercentage }}</td>
				  </tr>
				  <tr>
				    <th>Average Game Score</th>
				    <td>{{ meanGameScorePercentage }}</td>
				  </tr>
				  <tr>
				    <th>Percentage Owned</th>
				    <td>{{ gamesOwnedPercentage ? gamesOwnedPercentage : 'N/A' }}</td>
				  </tr>
				  <tr>
				    <th>Reviews</th>
				    <td>{{ this.game.reviews_count }}</td>
				  </tr>
				  <tr>
				    <th>Recommendations</th>
				    <td>{{ this.game.recommendations_count }}</td>
				  </tr>
				</tbody>
			</table>
    	</div>

    </div>

</div>
</template>

<script>
import PieChart from '../../../utilities/charts/Pie.js'
import DoughnutChart from '../../../utilities/charts/Doughnut.js'
import BarChart from '../../../utilities/charts/Bar.js'

export default {
	props: ['game'],
  	components:{ PieChart, DoughnutChart, BarChart },
    data() {
    	return {
    		contributionBarOptions: {
    			scales: { yAxes: [{ ticks: {beginAtZero:true}, scaleLabel: {display: true, labelString: 'Amount'} }] },
    			legend: { display: false },
    			maintainAspectRatio: false,
			},
    		scoreBarOptions: {
    			scales: { 
    				yAxes: [{ ticks: {beginAtZero:true}, scaleLabel: {display: true, labelString: 'Number of Users'} }], 
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
			var completedCount = _.filter(this.game.libraries, ['play_status_id', 3]).length
			var total = this.game.libraries.length //Subtract continuous and dropped??
			var completionPercentage = (completedCount/total)*100

			var roundedPercentage = parseFloat(completionPercentage).toFixed(2);
			if(isNaN(roundedPercentage)) {return 'N/A' }
			return roundedPercentage + '%'
		},
		gamesTotal() {
			return this.game.libraries.length
		},
		gamesOwned() {
			return _.filter(this.game.libraries, ['own', 1]).length
		},
		gamesOwnedPercentage() {
			var ownedPercentage = (this.gamesOwned/this.gamesTotal)*100
			var roundedPercentage = parseFloat(ownedPercentage).toFixed(2);

			if(isNaN(roundedPercentage)) {return null }
			return roundedPercentage + '%'
		},
		meanGameScorePercentage() {
			var libraries = _.filter(this.game.libraries, function(o) { return (o.score != null); })
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
			        	_.filter(this.game.libraries, ['play_status_id', 1]).length,
			        	_.filter(this.game.libraries, ['play_status_id', 2]).length,
			        	_.filter(this.game.libraries, ['play_status_id', 3]).length,
			        	_.filter(this.game.libraries, ['play_status_id', 4]).length,
			        	_.filter(this.game.libraries, ['play_status_id', 5]).length,
			        	_.filter(this.game.libraries, ['play_status_id', 6]).length,
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
			var filteredOwn = _.filter(this.game.libraries, ['own', 1])
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
		scoreDistribution() {
			var libraries = _.filter(this.game.libraries, function(o) { return (o.score != null); })
			return {
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
	}
}
</script>