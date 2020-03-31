<template>
		<dk-line-chart
			:chartData="chartdata"
			:chartOptions="options"
		></dk-line-chart>
</template>

<script>
import gql from 'graphql-tag';

export default {
	/*
	 * The component's data.
	 */
	data() {
		return {
			appOpened: [],
			options: {
				scales: {
					xAxes: [{
						type: 'time',
						distribution: 'linear',
						time: {
							unit: 'day'
						}
					}],
				}
			},
		};
	},

	apollo: {
		appOpened: gql`query{
			appOpened{
				date
				value
			}
		}`,
	},

	computed: {
		chartdata: function() {
			var events = [];
			for (var i = 0; i < this.appOpened.length; i++) {
				events.push({t: this.appOpened[i]["date"], y: this.appOpened[i]["value"]});
			}
			return { 
				datasets: [
				{
					label: this.$lang.get('home.app-opened'),
					lineTension: 0,
					borderColor: '#249EBF',
					pointBackgroundColor: 'white',
					backgroundColor: 'rgba(36, 158, 191, 0.1)',
					pointBorderColor: '#249EBF',
					fill: true,
					data: events
				}
			],
		};
		},
	},
}
</script>

