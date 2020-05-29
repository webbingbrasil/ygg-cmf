import { Controller } from 'stimulus';
import { Chart } from 'frappe-charts/dist/frappe-charts.min.esm';

export default class extends Controller {

    /**
     *
     */
    connect() {
        let regions = JSON.parse(this.data.get('regions'));
        let markers = JSON.parse(this.data.get('markers'));

        let data = {
            labels: JSON.parse(this.data.get('labels')),
            datasets: JSON.parse(this.data.get('datasets')),
        };

        if(regions.length > 0) {
            data.yRegions = regions;
        }

        if(markers.length > 0) {
            data.yMarkers = markers;
        }

        this.chart = new Chart(this.data.get('parent'), {
            data,
            title: this.data.get('title'),
            type: this.data.get('type'),
            height: this.data.get('height'),
            colors: JSON.parse(this.data.get('colors')),
            axisOptions: JSON.parse(this.data.get('axisOptions')),
            barOptions: JSON.parse(this.data.get('barOptions')),
            lineOptions: JSON.parse(this.data.get('lineOptions')),
            tooltipOptions: {
                formatTooltipX: d => this.formatTooltip(d, this.data.get('formatTooltipX')).toUpperCase(),
                formatTooltipY: d => this.formatTooltip(d, this.data.get('formatTooltipY')),
            }
        });

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', this.drawEvent());
    }

    formatTooltip(value, format) {
        if (value === undefined) {
            value = 0;
        }
        return format.replace('{d}', value)
    }

    /**
     *
     * @returns {Function}
     */
    drawEvent() {
        return () => {
            this.chart.draw();
        };
    }

    /**
     *
     */
    export() {
        this.chart.export();
    }

    /**
     *
     */
    disconnect() {
        $(document).off('shown.bs.tab', 'a[data-toggle="tab"]', this.drawEvent());
    }
}
