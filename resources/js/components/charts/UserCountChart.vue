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
			userCount: {
				'added': [],
				'deleted': [],
				'all': [],
			},
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
		userCount: gql`query{
			userCount {
				added {
					date
					value
				}
				deleted {
					date
					value
				}
				all {
					date
					value
				}
			}
		}`,
	},

	computed: {
		chartdata: function() {
			var added = [];
			for (var i = 0;  i< this.userCount['added'].length; i++) {
				added.push({
					t: new Date(this.userCount['added'][i]['date']),
					y: this.userCount['added'][i]['value']
				});
			}

			var deleted = [];
			for (var i = 0;  i< this.userCount['deleted'].length; i++) {
				deleted.push({
					t: new Date(this.userCount['deleted'][i]['date']),
					y: this.userCount['deleted'][i]['value']
				});
			}

			var all = [];
			for (var i = 0;  i< this.userCount['all'].length; i++) {
				all.push({
					t: new Date(this.userCount['all'][i]['date']),
					y: this.userCount['all'][i]['value']
				});
			}

			return {
				datasets: [
					{
						label: this.$lang.get('home.deleted-users'),
						lineTension: 0,
						borderColor: 'rgba(210, 70, 40, 1)',
						pointBackgroundColor: 'white',
						pointBorderColor: '#249EBF',
						fill: false,
						data: deleted
					},
					{
						label: this.$lang.get('home.added-users'),
						spanGaps: false,
						lineTension: 0,
						borderColor: 'rgba(50, 220, 50, 1)',
						pointBackgroundColor: 'white',
						pointBorderColor: '#249EBF',
						fill: false,
						data: added
					},
					{
						label: this.$lang.get('home.all-users'),
						lineTension: 0,
						borderColor: '#249EBF',
						pointBackgroundColor: 'white',
						backgroundColor: 'rgba(36, 158, 191, 0.1)',
						pointBorderColor: '#249EBF',
						fill: true,
						data: all
					}
				],
			};
		}
	},
}
</script>

