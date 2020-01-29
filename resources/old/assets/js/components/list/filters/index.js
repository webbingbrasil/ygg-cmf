import YggFilterDateRange from './FilterDateRange';
import YggFilterSelect from './FilterSelect';

export function filterByType(type) {
    if (type === 'select') {
        return YggFilterSelect;
    } else if (type === 'daterange') {
        return YggFilterDateRange;
    }
}

export {
    YggFilterSelect,
    YggFilterDateRange,
}
