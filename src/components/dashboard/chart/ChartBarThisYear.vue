<template>
  <div style="width: 95%">
        <div class="container card">
            <!-- {{dataChart}} -->
            <div class="card-header">
                <h4 class="text-success card-title mb-0"><b> This year </b></h4>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="chartjs-bar"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js';
export default {
    props: [
        'data', 'lab'
    ],
    computed: {
        labels(){
            return this.lab.map(item => {
                return item.nama_lab
            })
        },
        dataChart(){

            var d = new Date();

            var background = ['violet', 'red', 'yellow', 'blue', 'orange', 'violet', 'brown', 'lightseagreen', 'Navy', 'Moccasin'];
            var color = 0

            return this.lab.map(item => {

                var jan = 0
                var feb = 0
                var mar = 0
                var apr = 0
                var may = 0
                var jun = 0
                var jul = 0
                var aug = 0
                var sep = 0
                var oct = 0
                var nov = 0
                var dec = 0

                

                this.data.filter(bill => {
                    if (bill.nama_lab == item.nama_lab) {
                        color = color + 1
                    }

                    let data_billing = new Date(bill.date_time)

                    if (d.getFullYear() == data_billing.getFullYear() && bill.nama_lab == item.nama_lab) {
                        if ( data_billing.getMonth() == 0 ) {
                            jan = jan + 1
                        }else if( data_billing.getMonth() == 1 ){
                            feb = feb + 1
                        }else if( data_billing.getMonth() == 2 ){
                            mar = mar + 1
                        }else if( data_billing.getMonth() == 3 ){
                            apr = apr + 1
                        }else if( data_billing.getMonth() == 4 ){
                            may = may + 1
                        }else if( data_billing.getMonth() == 5 ){
                            jun = jun + 1
                        }else if( data_billing.getMonth() == 6 ){
                            jul = jul + 1
                        }else if( data_billing.getMonth() == 7 ){
                            aug = aug + 1
                        }else if( data_billing.getMonth() == 8 ){
                            sep = sep + 1
                        }else if( data_billing.getMonth() == 9 ){
                            oct = oct + 1
                        }else if( data_billing.getMonth() == 10 ){
                            nov = nov + 1
                        }else if( data_billing.getMonth() == 11 ){
                            dec = dec + 1
                        }
                    }

                })
                // color = color++
                // console.log('color', color);

                return {
                    label: item.nama_lab,
                    backgroundColor: background[color],
                    borderColor: background[color],
                    hoverBackgroundColor: background[color],
                    hoverBorderColor: background[color],
                    data: [jan, feb, mar, apr, may, jun, jul, aug, sep, oct, nov, dec],
                    barPercentage: .75,
                    categoryPercentage: .5
                }
            })
        }
    },
    methods: {
        chartBar(){
            new Chart(document.getElementById("chartjs-bar"), {
				type: "bar",
				data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: this.dataChart
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							stacked: false,
							ticks: {
								stepSize: 20
							}
						}],
						xAxes: [{
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
            });
        }
    },
    
    mounted() {
        this.chartBar()
    }
}
</script>

<style>

</style>