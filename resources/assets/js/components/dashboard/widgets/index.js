import YggWidgetPanel from './Panel';
import YggWidgetChart from './chart/Chart';

export function widgetByType(type) {
    if(type === 'graph') {
        return YggWidgetChart;
    } else if(type === 'panel') {
        return YggWidgetPanel;
    }
}

export {
    YggWidgetChart,
    YggWidgetPanel
};