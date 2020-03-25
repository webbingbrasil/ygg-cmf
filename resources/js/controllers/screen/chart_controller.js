import { Controller } from 'stimulus';
import { Chart } from 'frappe-charts/dist/frappe-charts.min.esm';

export default class extends Controller {

    /**
     *
     */
    connect() {
        let formatTooltip = this.data.get('formatTooltip');
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
                formatTooltipX: d => formatTooltip.replace('{d}', d).toUpperCase(),
                formatTooltipY: d => formatTooltip.replace('{d}', d),
            }
        });

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', this.drawEvent());
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
