<script>
import { Line, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins;

export default {
    extends: Line,
    mixins: [reactiveProp],
    props: {
        chartOptions: {
            required: false,
            type: Object,
            default: {}
        },
        'css-classes': {
            type: String,
            default: 'relative',
        },
        width: {
            default: null,
            type: Number
        },
        height: {
            default: null,
            type: Number
        },
    },
    data () {
        return {
            defaultOptions: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 0.8,
            }
        }
    },
    mounted () {
        console.log(this.mergedOptions);

        this.renderChart(this.chartData, this.options)
    },
    computed: {
        options: function () {
            let opts = {}
            Object.assign(opts, this.defaultOptions, this.chartOptions)
            return opts
        }
    }
}
</script>
