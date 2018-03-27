// // Pie.js
// import { Doughnut } from 'vue-chartjs'

// export default {
//   extends: Doughnut,
//   props: ['data', 'options'],
//   mounted () {
//     this.renderChart(this.data, this.options)
//   }
// }

import { Doughnut, mixins } from 'vue-chartjs'
const { reactiveProp } = mixins

export default {
  extends: Doughnut,
  mixins: [reactiveProp],
  props: ['options'],
  mounted () {
    // Plugins
	// this.addPlugin({
	// 	id: 'my-plugin',
	// 	afterDraw: function (chart) {
	// 	  	if (chart.data.datasets.length === 0) {
	// 	    	// No data is present
	// 	      var ctx = chart.chart.ctx;
	// 	      var width = chart.chart.width;
	// 	      var height = chart.chart.height
	// 	      chart.clear();
		      
	// 	      ctx.save();
	// 	      ctx.textAlign = 'center';
	// 	      ctx.textBaseline = 'middle';
	// 	      ctx.font = "16px normal 'Helvetica Nueue'";
	// 	      ctx.fillText('No data to display', width / 2, height / 2);
	// 	      ctx.restore();
	// 	    }
	// 	}
	// })

    // this.chartData is created in the mixin.
    // If you want to pass options please create a local options object
    // 
    // 
    // 
    // 
    // var tooltips = { tooltips : {
    //     callbacks: {
    //         label: function(tooltipItem, data) {
    //             var allData = data.datasets[tooltipItem.datasetIndex].data;
    //             var tooltipLabel = data.labels[tooltipItem.index];
    //             var tooltipData = allData[tooltipItem.index];
    //             var total = 0;
    //             for (var i in allData) {
    //                 total += allData[i];
    //             }
    //             var tooltipPercentage = Math.round((tooltipData / total) * 100);
    //             return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
    //         }
    //     }
    // } }
    // var customOptions = this.options
    // var newOptions = customOptions.push(tooltips)
    this.renderChart(this.chartData, this.options)
  }
}
