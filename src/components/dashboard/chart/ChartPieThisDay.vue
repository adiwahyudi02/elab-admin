<template>
  <div>
    <div class="card flex-fill w-100">
        <div class="card-header">
            <h4 class="text-success card-title mb-0"><b>This day</b></h4>
        </div>
        <div class="card-body d-flex">
            <div class="align-self-center w-100">
                <div class="py-3">
                    <div class="chart chart-xs">
                        <canvas id="chartjs-dashboard-pie-this-day"></canvas>
                    </div>
                </div>

                <table class="table mb-0">
                    <tbody>
                        <tr v-for="(label, index) in labels" :key="label">
                            <td>{{label}}</td>
                            <td class="text-right">{{ dataChart[index] }}</td>
                        </tr>
                    </tbody>
                </table>
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

            return this.lab.map(item => {
                let a = this.data.filter(bill => {
                    
                    if ( bill.nama_lab == item.nama_lab) {
                        let data_billing = new Date(bill.date_time)
                        return d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate() == data_billing.getFullYear()+'-'+(data_billing.getMonth()+1)+'-'+data_billing.getDate();
                    }
                })

                return a.length

            })
        }
    },
    methods: {
        chartPie(){
            new Chart(document.getElementById("chartjs-dashboard-pie-this-day"), {
            type: 'pie',
            data: {
                labels: this.labels,
                datasets: [{
                data: this.dataChart,
                backgroundColor: [
                    'yellow', 'red', 'Pink', 'blue', 'orange', 'violet', 'brown', 'lightseagreen', 'Navy', 'Moccasin'
                ],
                borderWidth: 5
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                display: false
                },
                cutoutPercentage: 75
            }
            });
        },
    },
    mounted() {
        this.chartPie()
    }
}   
</script>

<style>

</style>