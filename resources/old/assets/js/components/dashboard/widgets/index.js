import YggWidgetPanel from './Panel';
import YggWidgetForm from './Form';
import YggWidgetList from './List';
import YggWidgetChart from './chart/Chart';

export function widgetByType(type) {
    switch (type) {
        case 'graph':
            return YggWidgetChart;
        case 'panel':
            return YggWidgetPanel;
        case 'form':
            return YggWidgetForm;
        case 'list':
            return YggWidgetList;
    }
}

export {
    YggWidgetChart,
    YggWidgetPanel,
    YggWidgetForm,
    YggWidgetList
};